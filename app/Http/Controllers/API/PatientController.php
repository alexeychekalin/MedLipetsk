<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PatientsController;
use App\Http\Resources\PatientsResource;
use App\Http\Resources\PatientSummaryResource;
use App\Http\Resources\PaymentsResource;
use App\Http\Resources\TreatmentPlansResource;
use App\Models\Patients;
use App\Models\Payments;
use App\Models\TreatmentPlans;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function createPatient(Request $request)
    {
        $dc = new PatientsController();
        return $dc-> store($request);
    }
    public function updatePatient(Request $request, Patients $patient)
    {
        $dc = new PatientsController();
        return $dc-> update($request, $patient);
    }
    public function getPatient()
    {
        $dc = new PatientsController();
        return $dc-> index();
    }
    public function getOnePatient($patient_id)
    {
        $patient = Patients::find($patient_id);
        if (!$patient) {
            return response()->json(['error' => 'Пациент не найден'], 404);
        }
        return response()->json($patient);
    }

    public function deletePatient($id)
    {
        $patient = Patients::find($id);
        if (!$patient) {
            return response()->json(['error' => 'Пациент не найден'], 404);
        }
        $patient->delete();
        return response()->json(['message' => 'Пациент успешно удален']);
    }

    public function lastPatient(Request $request)
    {
        $limit = $request->input('limit');

        if ($limit !== null && !ctype_digit($limit)) {
            return response()->json(['error' => 'Параметр limit должен быть числом'], 400);
        }
        $limit = $limit !== null ? (int)$limit : null;
        $query = Patients::orderBy('created_at', 'desc');
        if ($limit !== null) {
            $query->limit($limit);
        }
        $patients = $query->get();
        return PatientSummaryResource::collection($patients);
    }

    public function planPatient()
    {
        // Текущая дата
        $now = now();
        $plans = TreatmentPlans::where('expiration_date', '>', $now)
            ->orderBy('starting_date', 'desc')
            ->get();
        return TreatmentPlansResource::collection($plans);
    }

    public function balancePatient()
    {
        // Предположим, что в модели Patients есть поле 'balance'
        $patients = Patients::where('balance', '>', 0)
            ->orderBy('balance', 'asc')
            ->get();

         return PatientsResource::collection($patients);
    }

    public function paymentsPatient($id)
    {
        $payments = Payments::where('patient_id', $id)->get();
        return PaymentsResource::collection($payments);
    }

    public function findPatient(Request $request)
    {
        $query = $request->input('search');
        if (!$query) {
            return response()->json([]);
        }
        $patients = Patients::where(function($q) use ($query) {
            $q->where('second_name', 'ilike', '%' . $query . '%')
                ->orWhere('first_name', 'ilike', '%' . $query . '%')
                ->orWhere('patronymic_name', 'ilike', '%' . $query . '%')
                ->orWhere('phone_number', 'ilike', '%' . $query . '%');
        })->get();

        $data = $patients->map(function($patients) {
            return [
                'id' => $patients->id,
                'second_name' => $patients->second_name,
                'first_name' => $patients->first_name,
                'patronymic_name' => $patients->patronymic_name,
                'phone_number' => $patients->phone_number,
            ];
        });

        return response()->json($data);
    }
}
