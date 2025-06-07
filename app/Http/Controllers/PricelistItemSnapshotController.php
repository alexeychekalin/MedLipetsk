<?php

namespace App\Http\Controllers;

use App\Models\PricelistItemSnapshot;
use Illuminate\Http\Request;
use App\Http\Resources\PricelistItemSnapshotResource;

class PricelistItemSnapshotController extends Controller
{
    public function index()
    {
        $snapshots = PricelistItemSnapshot::with('pricelistItem_rel')->get();
        return PricelistItemSnapshotResource::collection($snapshots);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomenklature' => 'nullable|string',
            'id_pricelist_item' => 'nullable|exists:pricelist_items,id',
            'price' => 'required|numeric',
            'costprice' => 'required|numeric',
            'fixedsalary' => 'nullable|numeric',
            'fixedagentfee' => 'nullable|numeric',
            'date_start' => 'nullable|date',
            'date_finish' => 'nullable|date',
        ]);
        $snapshot = PricelistItemSnapshot::create($validated);
        return new PricelistItemSnapshotResource($snapshot);
    }

    public function show($id)
    {
        $item = PricelistItemSnapshot::with('pricelistItem_rel')->findOrFail($id);
        return new PricelistItemSnapshotResource($item);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nomenklature' => 'nullable|string',
            'id_pricelist_item' => 'nullable|exists:pricelist_items,id',
            'price' => 'required|numeric',
            'costprice' => 'required|numeric',
            'fixedsalary' => 'nullable|numeric',
            'fixedagentfee' => 'nullable|numeric',
            'date_start' => 'nullable|date',
            'date_finish' => 'nullable|date',
        ]);
        $snapshot = PricelistItemSnapshot::with('pricelistItem_rel')->findOrFail($id);
        $snapshot->update($validated);
        $snapshot->refresh();  // обязательно, если нужно убедиться, что модель актуальна
        return new PricelistItemSnapshotResource($snapshot);
    }

    public function destroy(PricelistItemSnapshot $snapshot)
    {
        $snapshot->delete();
        return response()->json(['message' => 'История стоимости услуг удалена']);
    }
}
