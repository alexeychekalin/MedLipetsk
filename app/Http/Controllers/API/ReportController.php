<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ReportsController;
use App\Http\Resources\PaymentsResource;
use App\Http\Resources\ReportResource;
use App\Models\Department;
use App\Models\Doctors;
use App\Models\MedicalServices;
use App\Models\PatientAppointments;
use App\Models\Patients;
use App\Models\PaymentPurpose;
use App\Models\Payments;
use App\Models\PricelistItem;
use App\Models\Receipts;
use App\Models\Report;
use App\Models\Salaries;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function createReport(Request $request)
    {
        $dc = new ReportsController();
        return $dc-> store($request);
    }

    public function createPaymentToReport(Request $request)
    {
        /*
        $todayDate = date('Y-m-d'); // текущая дата в формате Y-m-d
        $report = \App\Models\Report::where('date', $todayDate)->first();
        if (!$report) {
            return response()->json(['error' => 'Отчет за сегодня не найден'], 404);
        }

        $dc = new ReportsController();
        $dc-> store($request);
        */
    }

    public function cancelPayment($payment_id)
    {
        $payment = Payments::find($payment_id);
        if (!$payment) response()->json(['error' => 'Платеж не найден'], 404);

        $paymentDate = Carbon::parse($payment->date)->toDateString();
        $report = Report::where('date_cash', $paymentDate)->first();
        if (!$report) {
            return response()->json(['error' => 'Отчет не найден'], 404);
        }

        $payment = Payments::findOrFail($payment_id);

        $totalAmount = 0;
        foreach ($payment->methods as $method) {
            if (isset($method['value'])) {
                $totalAmount += floatval($method['value']);
            }
        }
        $receipt = Receipts::findOrFail($payment->receipt_id);
        if (!$receipt) {
            return response()->json(['error' => 'Связанный чек не найден'], 404);
        }
        $totalPrice = $receipt->total_amount - $receipt->discount;
        if($totalAmount-$totalPrice != 0){
            $patient = Patients::findOrFail($receipt->patient_id);
            $patient->updateBalance(increment: -($totalAmount-$totalPrice));
        }
        $payment->patient_id = NULL;
        $payment->save();

        // Отменяем начисления врачам
        $medical_services = MedicalServices::where('receipt_id', $payment->receipt_id)->get();
        foreach ($medical_services as $medicalService) {
            // agent
            $price_list_item = PricelistItem::findOrFail($medicalService->pricelist_item_id);
            if($price_list_item->fixedagentfee != NULL)
                $agenfee = $price_list_item->fixedagentfee;
            else
                $agenfee = $price_list_item->pice*$medicalService->quantity*0.1;
            // Обновляем баланс доктора
            $doctor = Doctors::findOrFail($medicalService->agent_id);
            $doctor->updateBalance(increment: -$agenfee);

            $category = Department::findOrFail($price_list_item->department_id);
            if($category->name != "Лабораторные исследования"){
                // performer
                if($price_list_item->fixedSalary != NULL)
                    $agenfee = $price_list_item->fixedSalary;
                else{
                    $salaries = Salaries::where('doctor_id', $medicalService->performer_id)->first();
                    $agenfee = $price_list_item->pice*$medicalService->quantity*$salaries->rate*0.1;
                }
                // Обновляем баланс доктора
                $doctor = Doctors::findOrFail($medicalService->performer_id);
                $doctor->updateBalance(increment: -$agenfee);
            }
        }
        //переводим статус приемов в "Пришел"
        $appoitmens = PatientAppointments::where('receipt_id', $payment->receipt_id)->get();
        foreach ($appoitmens as $appoitmen) {
            $appoitmen->status = "Пришел";
            $appoitmen->save();
        }
        $payment->delete();

        // Находим или создаем отчет за сегодня
        $today = date('Y-m-d');
        $report = Report::firstOrCreate(
            ['date' => $today],
            ['total_amount' => 0, 'startingCash' => 0]
        );
        $report->startingcash += -$totalAmount;
        $report->save();
        // Вернуть отчет за сегодня
        return response()->json(new ReportResource($report));
    }
    public function deletePayment($payment_id)
    {
        $payment = Payments::find($payment_id);
        if (!$payment) response()->json(['error' => 'Платеж не найден'], 404);
        //return $payment->date;
        $paymentDate = Carbon::parse($payment->date)->toDateString();
        $report = Report::where('date_cash', $paymentDate)->first();
        if (!$report) {
            return response()->json(['error' => 'Отчет не найден'], 404);
        }
        $payment->delete();

        // Получаем остальные платежи этого отчета за тот же день
        $remainingPayments = Payments::whereDate('date', $paymentDate)
            ->get();

        $response = [
            'date' => \Carbon\Carbon::parse($report->date_cash)
                ->setTimezone('Europe/Moscow')
                ->toIso8601String(),
            'starting_cash' => $report->startingcash ?? 0,
            'payments' => PaymentsResource::collection($remainingPayments),
            //'closed' => false,
        ];
        return response()->json($response);
    }

    public function getReports(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
        ]);
        $report = Report::where('date_cash', $validated['date'])->first();

        if (!$report) {
            return response()->json(['error' => 'Отчет за этот день не найден'], 404);
        }
        $remainingPayments = Payments::whereDate('date', $validated['date'])
            ->get();

        $response = [
            'date' => \Carbon\Carbon::parse($report->date_cash)
                ->setTimezone('Europe/Moscow')
                ->toIso8601String(),
            'starting_cash' => $report->startingcash ?? 0,
            'payments' => PaymentsResource::collection($remainingPayments),
            //'closed' => false,
        ];
        return response()->json($response);
    }

    public function changePayMethod(Request $request)
    {
        $validated = $request->validate([
            'payment_id' => 'required|uuid|exists:payments,id',
            'methods' => 'required|array', // Передаете массив методов оплаты
        ]);

        $payment = Payments::findOrFail($validated['payment_id']);

        $payment->methods = $validated['methods'];
        $payment->save();

        $paymentDate = Carbon::parse($payment->date);

        $report = Report::whereDate('date_cash', $paymentDate->toDateString())->first();

        if (!$report) {
            return response()->json(['error' => 'Отчет за этот день не найден'], 404);
        }
        $payments = Payments::whereDate('date', $paymentDate->toDateString())
            ->get();

        $response = [
            'date' => \Carbon\Carbon::parse($report->date_cash)
                ->setTimezone('Europe/Moscow')
                ->toIso8601String(),
            'starting_cash' => $report->startingcash ?? 0,
            'payments' => PaymentsResource::collection($payments),
            //'closed' => false,
        ];
        return response()->json($response);

    }

    public function getReportsPeriod(Request $request)
    {
        $validated = $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:start_date',
        ]);

        $reports = Report::whereBetween('date_cash', [$validated['from'], $validated['to']])
            ->orderBy('date_cash', 'asc')
            ->get();

        if ($reports->isEmpty()) {
            return response()->json(['message' => 'Нет отчетов за указанный период'], 404);
        }

        $result = [];

        foreach ($reports as $report) {
            $payments = Payments::whereDate('date', $report->date_cash)
                ->get();

            $result[] = [
                'date' => \Carbon\Carbon::parse($report->date_cash)
                    ->setTimezone('Europe/Moscow')
                    ->toIso8601String(),
                'starting_cash' => $report->startingcash ?? 0,
                'payments' => PaymentsResource::collection($payments),
               // 'closed' => false, // или логика
            ];
        }

        return response()->json($result);
    }

    public function makeMedical(Request $request, $patient_id)
    {
        // Валидация данных
        $validated = $request->validate([
            'date' => 'required|date',
            'purpose' => 'nullable|exists:dict_payment_purposes,id',
            'details' => 'required|string',
            'methods' => 'required|json',
            'receipt_id' => 'nullable|uuid|exists:receipts,id',
            'created_by' => 'nullable|uuid|exists:users,id',
            'doctor_id' => 'nullable|uuid|exists:doctors,id',
            'patient_id' => 'nullable|uuid|exists:patients,id',
        ]);

        //считаем баланс платежа
        $totalAmount = 0;
        foreach ($validated['methods'] as $method) {
            if (isset($method['value'])) {
                $totalAmount += floatval($method['value']);
            }
        }
        $receipt = Receipts::findOrFail($validated['receipt_id']);
        if (!$receipt) {
            return response()->json(['error' => 'Связанный чек не найден'], 404);
        }
        $totalPrice = $receipt->total_amount - $receipt->discount;
        if($totalAmount-$totalPrice != 0)
            $this->updateBalanceWithoutRecord($patient_id, $totalAmount - $totalPrice, $request);

        // Находим пациента
        $patient = Patients::findOrFail($patient_id);

        // Создаем платеж
        $payment = Payments::create([
            'date' => $validated['date'],
            'purpose' => $validated['purpose'],
            'details' => $validated['details'],
            'methods' => $validated['methods'],
            'patient_id' => $patient,
            'receipt_id' => $validated['receipt_id'] ?? null,
            'created_by' => $validated['created_by'] ?? null,
            'doctor_id' => $validated['doctor_id'] ?? null,
        ]);

        // Производим начисления врачам
        $medical_services = MedicalServices::where('receipt_id', $validated['receipt_id'])->get();
        foreach ($medical_services as $medicalService) {
            // agent
            $price_list_item = PricelistItem::findOrFail($medicalService->pricelist_item_id);
            if($price_list_item->fixedagentfee != NULL)
                $agenfee = $price_list_item->fixedagentfee;
            else
                $agenfee = $price_list_item->pice*$medicalService->quantity*0.1;
            // Обновляем баланс доктора
            $doctor = Doctors::findOrFail($medicalService->agent_id);
            $doctor->updateBalance(increment: $agenfee);

            $category = Department::findOrFail($price_list_item->department_id);
            if($category->name != "Лабораторные исследования"){
                // performer
                if($price_list_item->fixedSalary != NULL)
                    $agenfee = $price_list_item->fixedSalary;
                else{
                    $salaries = Salaries::where('doctor_id', $medicalService->performer_id)->first();
                    $agenfee = $price_list_item->pice*$medicalService->quantity*$salaries->rate*0.1;
                }
                // Обновляем баланс доктора
                $doctor = Doctors::findOrFail($medicalService->performer_id);
                $doctor->updateBalance(increment: $agenfee);
            }
        }
        //переводим статус приемов в "Завершен"
        $appoitmens = PatientAppointments::where('receipt_id', $validated['receipt_id'])->get();
        foreach ($appoitmens as $appoitmen) {
            $appoitmen->status = "Завершен";
            $appoitmen->save();
        }

        // Находим или создаем отчет за сегодня
        $today = date('Y-m-d');
        $report = Report::firstOrCreate(
            ['date' => $today],
            ['total_amount' => 0, 'startingCash' => 0]
        );

        $report->startingcash += $totalAmount;
        $report->save();

        // Вернуть отчет за сегодня
        return response()->json(new ReportResource($report));
    }

    public function updateBalanceWithoutRecord($patient_id, $increment, $request): void
    {
        $name = $increment < 0 ? 'Списание с баланса' : 'Пополнение баланса';
        $purpose = PaymentPurpose::where('name', $name)->first();
        Payments::create([
            'date' => $request['date'],
            'purpose' => $purpose->id,
            'details' => $request['details'],
            'methods' => "{\"cash\": $increment}",
            'patient_id' => $patient_id,
            'created_by' => $request['created_by'] ?? null,
        ]);

        // Обновляем баланс пациента
        $patient = Doctors::findOrFail($patient_id);
        $patient->updateBalance(increment: $increment);
    }

    public function makePayout(Request $request, $doctor_id)
    {
        // Валидация данных
        $validated = $request->validate([
            'date' => 'required|date',
            'purpose' => 'nullable|exists:dict_payment_purposes,id',
            'details' => 'required|string',
            'methods' => 'required|json',
            'receipt_id' => 'nullable|uuid|exists:receipts,id',
            'created_by' => 'nullable|uuid|exists:users,id',
            'doctor_id' => 'nullable|uuid|exists:doctors,id',
            'patient_id' => 'nullable|uuid|exists:patients,id',
        ]);

        // Находим врача
        $doctor = Doctors::findOrFail($doctor_id);

        // Создаем платеж
        $payment = Payments::create([
            'date' => $validated['date'],
            'purpose' => $validated['purpose'],
            'details' => $validated['details'],
            'methods' => $validated['methods'],
            'doctor_id' => $doctor_id,
            'receipt_id' => $validated['receipt_id'] ?? null,
            'created_by' => $validated['created_by'] ?? null,
            'patient_id' => $validated['patient_id'] ?? null,
        ]);

        // Находим или создаем отчет за сегодня
        $today = date('Y-m-d');
        $report = Report::firstOrCreate(
            ['date' => $today],
            ['total_amount' => 0, 'startingCash' => 0]
        );

        $totalAmount = 0;
        foreach ($validated['methods'] as $method) {
            if (isset($method['value'])) {
                $totalAmount += floatval($method['value']);
            }
        }

        $report->startingcash -= $totalAmount;
        $report->save();

        // Обновляем баланс доктора
        $doctor->updateBalance(increment: $totalAmount);

        // Вернуть отчет за сегодня
        return response()->json(new ReportResource($report));
    }

    public function makeBalancePayment(Request $request, $id){
        $validated = $request->validate([
            'date' => 'required|date',
            'purpose' => 'nullable|exists:dict_payment_purposes,id',
            'details' => 'required|string',
            'methods' => 'required|json',
            'receipt_id' => 'nullable|uuid|exists:receipts,id',
            'created_by' => 'nullable|uuid|exists:users,id',
            'doctor_id' => 'nullable|uuid|exists:doctors,id',
            'patient_id' => 'nullable|uuid|exists:patients,id',
        ]);

        // Проверка, есть ли такой доктор или пациент
        $doctor = Doctors::where('id', $id)->first();
        $patient = Patients::where('id', $id)->first();

        $associatedId = null;
        $type = null;

        if ($doctor) {
            $associatedId = $doctor->id;
            $type = 'doctor';
        } elseif ($patient) {
            $associatedId = $patient->id;
            $type = 'patient';
        } else {
            return response()->json(['error' => 'Доктор или пациент не найден'], 404);
        }

        // Создаём платеж
        $paymentData = [
            'id' => $validated['id'],
            'date' => $validated['date'],
            'purpose' => $validated['purpose'],
            'details' => $validated['details'],
            'methods' => $validated['methods'],
            'receipt_id' => $validated['receipt_id'] ?? null,
            'created_by' => $validated['created_by'] ?? null,
        ];

        $totalAmount = 0;
        foreach ($validated['methods'] as $method) {
            if (isset($method['value'])) {
                $totalAmount += floatval($method['value']);
            }
        }

        if ($type === 'doctor') {
            $paymentData['doctor_id'] = $associatedId;
            $doctor->updateBalance(increment: $totalAmount);
        } else {
            $paymentData['patient_id'] = $associatedId;
            $patient->updateBalance(increment: $totalAmount);
        }

        $payment = Payments::create($paymentData);

        // Находим или создаем отчет за сегодня
        $today = date('Y-m-d');
        $report = Report::firstOrCreate(
            ['date' => $today],
            ['total_amount' => 0, 'startingCash' => 0]
        );
        $report->startingcash += $totalAmount;
        $report->save();
        // Вернуть отчет за сегодня
        return response()->json(new ReportResource($report));
    }

    public function makePayment(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'purpose' => 'nullable|exists:dict_payment_purposes,id',
            'details' => 'required|string',
            'methods' => 'required|json',
            'receipt_id' => 'nullable|uuid|exists:receipts,id',
            'created_by' => 'nullable|uuid|exists:users,id',
            'doctor_id' => 'nullable|uuid|exists:doctors,id',
            'patient_id' => 'nullable|uuid|exists:patients,id',
        ]);

         $todayDate = date('Y-m-d'); // текущая дата в формате Y-m-d
         $report = Report::where('date', $todayDate)->first();
         if (!$report) {
             return response()->json(['error' => 'Отчет за сегодня не найден'], 404);
         }

        $totalAmount = 0;
        foreach ($validated['methods'] as $method) {
            if (isset($method['value'])) {
                $totalAmount += floatval($method['value']);
            }
        }

        $report->startingcash += $totalAmount;
        $report->save();

        // Создаем платеж
        $payment = Payments::create([
            'date' => $validated['date'],
            'purpose' => $validated['purpose'],
            'details' => $validated['details'],
            'methods' => $validated['methods'],
            'doctor_id' => $validated['doctor_id'] ?? null,
            'receipt_id' => $validated['receipt_id'] ?? null,
            'created_by' => $validated['created_by'] ?? null,
            'patient_id' => $validated['patient_id'] ?? null,
        ]);
        return response()->json(new ReportResource($report));
    }
}
