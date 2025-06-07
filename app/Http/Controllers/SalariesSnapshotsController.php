<?php

namespace App\Http\Controllers;

use App\Models\SalariesSnapshot;
use Illuminate\Http\Request;
use App\Http\Resources\SalariesSnapshotResource;

class SalariesSnapshotsController extends Controller
{
    public function index()
    {
        return SalariesSnapshotResource::collection(SalariesSnapshot::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:Сдельная,Ежемесячная,Почасовая',
            'rate' => 'nullable|numeric',
            'monthly_amount' => 'nullable|integer',
            'hourly_amount' => 'nullable|integer',
            'doctor' => 'nullable|exists:doctors,id',
            'date_start' => 'nullable|date',
            'date_finish' => 'nullable|date',
        ]);
        $snapshot = SalariesSnapshot::create($validated);
        return new SalariesSnapshotResource($snapshot);
    }

    public function show($id)
    {
        $item = SalariesSnapshot::with('doctor_rel')->findOrFail($id);
        return new SalariesSnapshotResource($item);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:Сдельная,Ежемесячная,Почасовая',
            'rate' => 'nullable|numeric',
            'monthly_amount' => 'nullable|integer',
            'hourly_amount' => 'nullable|integer',
            'doctor' => 'nullable|exists:doctors,id',
            'date_start' => 'nullable|date',
            'date_finish' => 'nullable|date',
        ]);

        $salariessnapshot = SalariesSnapshot::with('doctor_rel')->findOrFail($id);
        $salariessnapshot->update($validated);
        $salariessnapshot->refresh();  // обязательно, если нужно убедиться, что модель актуальна
        return new SalariesSnapshotResource($salariessnapshot);
    }

    public function destroy(SalariesSnapshot $snapshot)
    {
        $snapshot->delete();
        return response()->json(['message' => 'История зарплаты удалена']);
    }
}
