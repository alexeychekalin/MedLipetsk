<?php

namespace App\Http\Controllers;

use App\Models\Receipts;
use Illuminate\Http\Request;
use App\Http\Resources\ReceiptsResource;

class ReceiptsController extends Controller
{
    public function index()
    {
        return ReceiptsResource::collection(Receipts::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'total_amount' => 'required|numeric',
            'discount' => 'nullable|numeric',
        ]);
        $receipt = Receipts::create($validated);
        return new ReceiptsResource($receipt);
    }

    public function show(Receipts $receipt)
    {
        return new ReceiptsResource($receipt);
    }

    public function update(Request $request, Receipts $receipt)
    {
        $validated = $request->validate([
            'total_amount' => 'required|numeric',
            'discount' => 'nullable|numeric',
        ]);
        $receipt->update($validated);
        return new ReceiptsResource($receipt);
    }

    public function destroy(Receipts $receipt)
    {
        $receipt->delete();
        return response()->json(['message' => 'Чек удален']);
    }
}
