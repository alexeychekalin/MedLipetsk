<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentsResource;
use App\Models\Doctors;
use App\Http\Controllers\DoctorsController;
use App\Models\Payments;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function createDoctor(Request $request)
    {
        $dc = new DoctorsController();
        return $dc-> store($request);
    }
    public function updateDoctor(Request $request, Doctors $doctor)
    {
        $dc = new DoctorsController();
        return $dc-> update($request, $doctor);
    }
    public function getDoctor()
    {
        $dc = new DoctorsController();
        return $dc-> index();
    }
    public function getOneDoctor($doctor_id)
    {
        $doctor = Doctors::find($doctor_id);
        if (!$doctor) {
            return response()->json(['error' => 'Врач не найден'], 404);
        }
        return response()->json($doctor);
    }

    public function deleteDoctor($id)
    {
        $doctor = Doctors::find($id);
        if (!$doctor) {
            return response()->json(['error' => 'Врач не найден'], 404);
        }
        $doctor->delete();
        return response()->json(['message' => 'Врач успешно удален']);
    }

    public function paymentsDoctor($id)
    {
        $payments = Payments::where('doctor_id', $id)->get();
        return PaymentsResource::collection($payments);
    }

    public function findDoctor(Request $request)
    {
        $query = $request->input('search');
        if (!$query) {
            return response()->json([]);
        }
        $doctors = \App\Models\Doctors::where(function($q) use ($query) {
            $q->where('second_name', 'ilike', '%' . $query . '%')
                ->orWhere('first_name', 'ilike', '%' . $query . '%')
                ->orWhere('patronymic_name', 'ilike', '%' . $query . '%')
                ->orWhere('phone_number', 'ilike', '%' . $query . '%');
        })->get();

        $data = $doctors->map(function($doctor) {
            return [
                'id' => $doctor->id,
                'second_name' => $doctor->second_name,
                'first_name' => $doctor->first_name,
                'patronymic_name' => $doctor->patronymic_name,
                'phone_number' => $doctor->phone_number,
            ];
        });

        return response()->json($data);
    }
}
