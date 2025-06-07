<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use Illuminate\Http\Request;
use App\Http\Resources\PaymentsResource;

class PaymentsController extends Controller
{
    public function index()
    {
        //return PaymentsResource::collection(Payments::all());
        $items = Payments::with('doctor_id', 'patient_id')->get();
        return PaymentsResource::collection($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'purpose' => 'nullable|exists:dict_payment_purposes,id',
            'details' => 'required|string',
            'methods' => 'required|json',
            'receipt_id' => 'nullable|exists:receipts,id',
            'created_by' => 'nullable|exists:users,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'patient_id' => 'nullable|exists:patients,id',
        ]);
        $payment = Payments::create($validated);
        return new PaymentsResource($payment);
    }

    public function show(Payments $payment)
    {
        return new PaymentsResource($payment);
    }

    public function update(Request $request, Payments $payment)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'purpose' => 'nullable|exists:dict_payment_purposes,id',
            'details' => 'required|string',
            'methods' => 'required|json',
            'receipt_id' => 'nullable|exists:receipts,id',
            'created_by' => 'nullable|exists:users,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'patient_id' => 'nullable|exists:patients,id',
        ]);
        $payment->update($validated);
        return new PaymentsResource($payment);
    }

    public function destroy(Payments $payment)
    {
        $payment->delete();
        return response()->json(['message' => 'Платеж удален']);
    }
}
