<?php

namespace App\Http\Controllers;

use App\Models\PaymentPurpose;
use Illuminate\Http\Request;
use App\Http\Resources\PaymentPurposeResource;

class PaymentPurposeController extends Controller
{
    public function index()
    {
        $purposes = PaymentPurpose::all();
        return PaymentPurposeResource::collection($purposes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:dict_payment_purposes,name',
        ]);
        $purpose = PaymentPurpose::create($validated);
        return new PaymentPurposeResource($purpose);
    }

    public function show(PaymentPurpose $payment_purpose)
    {
        return new PaymentPurposeResource($payment_purpose);
    }

    public function update(Request $request, PaymentPurpose $payment_purpose)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:dict_payment_purposes,name,' . $payment_purpose->id,
        ]);
        $payment_purpose->update($validated);
        return new PaymentPurposeResource($payment_purpose);
    }

    public function destroy(PaymentPurpose $purpose)
    {
        $purpose->delete();
        return response()->json(['message' => 'Цель платежа удалена']);
    }
}
