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
}
