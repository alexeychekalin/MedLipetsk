<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorSchedulesResource;
use App\Http\Resources\NotesResource;
use App\Models\DoctorSchedules;
use App\Models\PatientAppointments;
use App\Models\Patients;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function schedulesByDay(Request $request)
    {
        $date = $request->query('date');
        if (!$date || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return response()->json(['error' => 'Неверный запрос (отсутствуют обязательные поля или неверный формат)'], 400);
        }

        // Получаем все расписания на дату вместе с note и patient_appointments с связью
        $schedules = DoctorSchedules::with([
            'note',
            'patientAppointments' => function ($query) {
                $query->orderBy('scheduled_time', 'asc');
            },
            'patientAppointments.patient',
            'patientAppointments.note'
        ])->whereDate('starting', $date)->get();

        return DoctorSchedulesResource::collection($schedules);
    }

    public function createSchedule(Request $request)
    {
        // Валидация
        $validated = $request->validate([
            'doctor_id' => 'required|uuid|exists:doctors,id', // предположим, что таблица doctors
            'cabinet' => 'required|integer',
            'starting' => 'required|date|after_or_equal:today',
            'ending' => 'required|date|after:starting',
        ]);

        $startTime = strtotime($validated['starting']);
        $endTime = strtotime($validated['ending']);

        $existingSchedule = DoctorSchedules::where('doctor_id', $validated['doctor_id'])
            ->where(function($query) use ($startTime, $endTime) {
                // Проверка на пересечение интервалов
                $query->whereBetween('starting', [date('Y-m-d H:i:s', $startTime), date('Y-m-d H:i:s', $endTime)])
                    ->orWhereBetween('ending', [date('Y-m-d H:i:s', $startTime), date('Y-m-d H:i:s', $endTime)])
                    ->orWhere(function($q) use ($startTime, $endTime) {
                        $q->where('starting', '<=', date('Y-m-d H:i:s', $startTime))
                            ->where('ending', '>=', date('Y-m-d H:i:s', $endTime));
                    });
            })
            ->exists();

        if ($existingSchedule) {
            return response()->json(['error' => 'У врача уже есть расписание на выбранный интервал'], 422);
        }

        // Создаем новое расписание
        $schedule = DoctorSchedules::create([
            'doctor_id' => $validated['doctor_id'],
            'cabinet' => $validated['cabinet'],
            'starting' => $validated['starting'],
            'ending' => $validated['ending'],
        ]);

        // Генерация записей на прием
        $startTime = strtotime($validated['starting']);
        $endTime = strtotime($validated['ending']);
        $intervalSeconds = config('app.default_duration'); // 10 минут

        $appointments = [];
        while ($startTime + $intervalSeconds <= $endTime+100) {
            $appointments[] = [
                'id' => \Illuminate\Support\Str::uuid(),
                'scheduled_time' => date('Y-m-d H:i:s', $startTime),
                'duration' => config('app.default_duration'),
                'patient_id' => null,
                'status' => 'available',
                'registration_date' => null,
                'registrar' => null,
                'receipt_id' => null,
                'schedule_id' => $schedule->id,
                'patient_comment' => null,
                'sms_notification_sent' => false,
            ];

            $startTime += $intervalSeconds;
        }

        // Вставляем записи
        PatientAppointments::insert($appointments);

        // Возврат (можно вернуть созданное расписание и список записей)
        $schedules = DoctorSchedules::with([
            'note',
            'patientAppointments' => function ($query) {
                $query->orderBy('scheduled_time', 'asc');
            },
            'patientAppointments.patient',
            'patientAppointments.note'
        ])->where('id', $schedule->id)->get();

        return DoctorSchedulesResource::collection($schedules);
    }

    public function registerPatient(Request $request)
    {
        // Валидация: patient_id обязательно
        $validated = $request->validate([
            'patient_id' => 'required|uuid|exists:patients,id',
            'appointment_id' => 'required|uuid|exists:patient_appointments,id',
            'duration' => 'integer',
            'registrar' => 'required|uuid|exists:users,id',
            'merged_receipt_id' => 'nullable|uuid|exists:receipts,id',
        ]);

        $appointment = PatientAppointments::find($validated['appointment_id']);
        if (!$appointment) {
            return response()->json(['error' => 'Расписание не найдено'], 404);
        }

        if($validated['duration'] > config('app.default_duration')) {
            $this->handleDurationIncrease($appointment, $validated['duration']);
        }

        $appointment->patient_id = $validated['patient_id'];
        $appointment->status = 'Зарегистрирован';
        $appointment->duration = $validated['duration'];
        $appointment->registrar = $validated['registrar'];
        $appointment->registration_date = Carbon::now('Europe/Moscow');
        if ($validated['merged_receipt_id']) {
            $appointment->receipt_id = $validated['merged_receipt_id'];
        }
        $appointment->save();

        $schedule = DoctorSchedules::with([
            'note',
            'patientAppointments.patient',
            'patientAppointments.note',
        ])->findOrFail($appointment->schedule_id);

        return new DoctorSchedulesResource($schedule);

    }

    public function registerPatientNew(Request $request)
    {
        return DB::transaction(function () use ($request) {
            // Валидация входных данных
            $validated = $request->validate([
                'patient' => [
                    'required',
                    'array',
                    'size:4',
                    'second_name' => 'required',
                    'first_name' => 'required',
                    'patronymic_name' => 'required',
                    'phone_number' => 'required',
                ],
                'appointment_id' => 'required|uuid|exists:patient_appointments,id',
                'duration' => 'integer',
                'registrar' => 'required|uuid|exists:users,id',
            ]);

            // Создаваем нового пациента
            $patientData = $validated['patient'];
            $newPatient = Patients::create([
                'id' => \Illuminate\Support\Str::uuid(), // или автоинкремент, если есть
                'second_name' => $patientData['second_name'],
                'first_name' => $patientData['first_name'],
                'patronymic_name' => $patientData['patronymic_name'],
                'phone_number' => $patientData['phone_number'],
            ]);

            // Получить запись приема, который нужно зарегистрировать
            $appointment = PatientAppointments::find($validated['appointment_id']);
            if (!$appointment) {
                return response()->json(['error' => 'Расписание не найдено'], 404);
            }

            // Проверяем и обновляем длительность, при необходимости
            if ($validated['duration'] > config('app.default_duration')) {
                $this->handleDurationIncrease($appointment, $validated['duration']);
            }

            // Назначить пациента
            $appointment->patient_id = $newPatient->id;
            $appointment->status = 'Зарегистрирован';
            $appointment->duration = $validated['duration'];
            $appointment->registrar = $validated['registrar'];
            $appointment->registration_date = Carbon::now('Europe/Moscow');
            $appointment->save();

            // Вернуть обновленное расписание
            $schedule = DoctorSchedules::with([
                'note',
                'patientAppointments' => function ($query) {
                    $query->orderBy('scheduled_time', 'asc');
                },
                'patientAppointments.patient',
                'patientAppointments.note',
            ])->findOrFail($appointment->schedule_id);

            return new DoctorSchedulesResource($schedule);
        });
    }

    public function cancelPatientAppointment(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $validated = $request->validate([
                'appointments_id' => 'required|uuid|exists:patient_appointments,id',
            ]);

            $app = PatientAppointments::with(['note', 'receipt'])->find($validated['appointments_id']);

            if (!$app) {
                return response()->json(['error' => 'Запись не найдена'], 404);
            }

            $scheduledTime = $app->scheduled_time;
            $duration = $app->duration ?? config('app.default_duration');

            $app->patient_id = null;
            $app->status = null;
            $app->registration_date = null;
            $app->registrar = null;
            $app->duration = config('app.default_duration');

            if ($app->note) {
                $app->note->delete();
            }

            $receipt = $app->receipt ?? null;
            if ($receipt) {
                $countRelated = PatientAppointments::where('receipt_id', $receipt->id)
                    ->where('id', '!=', $app->id)
                    ->count();

                if ($countRelated === 0) {
                    $receipt->delete();
                } else {
                    $app->receipt_id = null;
                }
            }

            $app->save();

            if ($duration > config('app.default_duration')) {
                $startTime = strtotime($scheduledTime);
                $endTime = $startTime + intdiv($duration, config('app.default_duration'))*config('app.default_duration');
                while ($startTime + config('app.default_duration') <= $endTime) {
                    $intervals[] = [
                        'id' => \Illuminate\Support\Str::uuid(),
                        'scheduled_time' => date('Y-m-d H:i:s', $startTime + config('app.default_duration')),
                        'duration' => config('app.default_duration'),
                        'status' => null,
                        'patient_id' => null,
                        'registration_date' => null,
                        'registrar' => null,
                        'receipt_id' => null,
                        'schedule_id' => $app->schedule_id,
                        'patient_comment' => null,
                        'sms_notification_sent' => false,
                    ];
                    $startTime += config('app.default_duration');
                }
                PatientAppointments::insert($intervals);
            }
            $schedule = DoctorSchedules::with([
                'note',
                'patientAppointments' => function ($query) {
                    $query->orderBy('scheduled_time', 'asc');
                },
                'patientAppointments.patient',
                'patientAppointments.note',
            ])->findOrFail($app->schedule_id);

            return new DoctorSchedulesResource($schedule);
        });
    }

    public function Bounds(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|uuid|exists:doctor_schedules,id',
            'starting' => 'required|date',
            'ending' => 'required|date|after_or_equal:starting',
        ]);
        $schedule = DoctorSchedules::findOrFail($validated['schedule_id']);

        $oldStart = strtotime($schedule->starting);
        $oldEnd = strtotime($schedule->ending);
        $newStart = strtotime($validated['starting']);
        $newEnd = strtotime($validated['ending']);

        // Обновляем интервал
        $schedule->starting = date('Y-m-d H:i:s', $newStart);
        $schedule->ending = date('Y-m-d H:i:s', $newEnd);
        $schedule->save();

        $appointments = PatientAppointments::where('schedule_id', $schedule->id)
            ->whereBetween('scheduled_time', [
                date('Y-m-d H:i:s', $oldStart),
                date('Y-m-d H:i:s', $oldEnd),
            ])->get();

        // Проверяем есть ли в старом интервале слоты с пациентами, которые не попадают в новый
        foreach ($appointments as $app) {
            if ($app->patient_id != null) {
                $slotTime = strtotime($app->scheduled_time);
                if ($slotTime < $newStart || $slotTime >= $newEnd) {
                    return response()->json(['error' => 'Есть занятые слоты вне нового интервала'], 409);
                }
            }
        }

        // Удаляем свободные слоты вне нового интервала
        PatientAppointments::where('schedule_id', $schedule->id)
            ->whereBetween('scheduled_time', [
                date('Y-m-d H:i:s', $oldStart),
                date('Y-m-d H:i:s', $oldEnd),
            ])
            ->whereNull('patient_id')
            ->delete();

        // Создаем новые слоты только в новом интервале
        $defaultDuration = config('app.default_duration');
        $startTime = $newStart;
        $newSlots = [];

        // Посчитаем, есть ли уже в базе слоты со временем в новом интервале с patient_id != null
        $existingBusySlots = PatientAppointments::where('schedule_id', $schedule->id)
            ->whereBetween('scheduled_time', [
                date('Y-m-d H:i:s', $newStart),
                date('Y-m-d H:i:s', $newEnd),
            ])
            ->where('patient_id', '!=', null)
            ->pluck('scheduled_time')->map(function($dt) {
                return strtotime($dt);
            })->toArray();

        while ($startTime + $defaultDuration <= $newEnd) {
            if (!in_array($startTime, $existingBusySlots)) {
                // Проверка, что такой слот с patient_id != null не существует
                $existsWithPatient = PatientAppointments::where('schedule_id', $schedule->id)
                    ->where('scheduled_time', date('Y-m-d H:i:s', $startTime))
                    ->where('patient_id', '!=', null)
                    ->exists();

                if (!$existsWithPatient) {
                    $newSlots[] = [
                        'id' => \Illuminate\Support\Str::uuid(),
                        'scheduled_time' => date('Y-m-d H:i:s', $startTime),
                        'duration' => $defaultDuration,
                        'schedule_id' => $schedule->id,
                    ];
                }
            }
            $startTime += $defaultDuration;
        }

        PatientAppointments::insert($newSlots);

        // Возвращение обновленного расписания
        $schedule = DoctorSchedules::with([
            'note',
            'patientAppointments.patient',
            'patientAppointments.note'
        ])->findOrFail($schedule->id);

        $schedule->patientAppointments = $schedule->patientAppointments->sortBy('scheduled_time');

        return new DoctorSchedulesResource($schedule);
    }

    public function createNoteSchedule(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|uuid|exists:doctor_schedules,id',
            'text' => 'required|string',
            'user_id' => 'required|uuid|exists:users,id',
        ]);
        // Найти заметку по schedule_id
        $note = \App\Models\Notes::where('doctor_schedule', $validated['schedule_id'])->first();

        if (!$note) {
            return response()->json(['error' => 'Заметка не найдена для этого расписания'], 404);
        }

        // Создаем заметку
        $note = \App\Models\Notes::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'text' => $validated['text'],
            'created_by' => $validated['user_id'],
            'doctor_schedule' => $validated['schedule_id'], // Связь с расписанием
        ]);

        return new NotesResource($note);
    }

    public function updateNoteSchedule(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|uuid|exists:doctor_schedules,id',
            'text' => 'required|string',
            'user_id' => 'required|uuid|exists:users,id',
        ]);

        // Найти заметку по schedule_id
        $note = \App\Models\Notes::where('doctor_schedule', $validated['schedule_id'])->first();

        if (!$note) {
            return response()->json(['error' => 'Заметка не найдена для этого расписания'], 404);
        }

        $note->text = $validated['text'];
        $note->updated_by = $validated['user_id'];
        $note->save();

        return new NotesResource($note);
    }

    public function createNoteAppointment(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'required|uuid|exists:patient_appointments,id',
            'text' => 'required|string',
            'user_id' => 'required|uuid|exists:users,id',
        ]);

        // Создаем заметку
        $note = \App\Models\Notes::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'text' => $validated['text'],
            'created_by' => $validated['user_id'],
            'patient_appointment' => $validated['appointment_id'], // связываем с назначением
        ]);

        return new NotesResource($note);
    }

    public function updateNoteAppointment(Request $request)
    {
        $validated = $request->validate([
            'appointment_id' => 'required|uuid|exists:patient_appointments,id',
            'text' => 'required|string',
            'user_id' => 'required|uuid|exists:users,id',
        ]);

        // Найти заметку связана с этим назначением
        $note = \App\Models\Notes::where('patient_appointment', $validated['appointment_id'])->first();

        if (!$note) {
            return response()->json(['error' => 'Заметка для записи на прием не найдена'], 404);
        }

        // Обновляем поля
        $note->text = $validated['text'];
        $note->updated_by = $validated['user_id'];
        $note->save();

        return new NotesResource($note);
    }

    private function handleDurationIncrease($appointment, $newDuration)
    {
        $currentEndTime = strtotime($appointment->scheduled_time) + $newDuration;
        $toDelete = PatientAppointments::where('schedule_id', $appointment->schedule_id)
            ->where('scheduled_time', '>', $appointment->scheduled_time)
            ->where('scheduled_time', '<', date('Y-m-d H:i:s', $currentEndTime))
            ->get();
        foreach ($toDelete as $app) {
            if ($app->patient_id !== null) {
                throw new \Exception('Невозможно удалить расписание с пациентом', 409);
            }
        }
        PatientAppointments::whereIn('id', $toDelete->pluck('id'))->delete();
    }

}

