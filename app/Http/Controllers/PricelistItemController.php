<?php

namespace App\Http\Controllers;

use App\Models\PricelistItem;
use Illuminate\Http\Request;
use App\Http\Resources\PricelistItemResource;

class PricelistItemController extends Controller
{
    public function index()
    {
        $items = PricelistItem::with('category_rel')->get();
        return PricelistItemResource::collection($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomenklature' => 'nullable|string',
            'category' => 'nullable|exists:dict_departments,id',
            'title' => 'required|string',
            'price' => 'required|numeric',
            'costprice' => 'required|numeric',
            'archived' => 'boolean',
            'fixedsalary' => 'nullable|numeric',
            'fixedagentfee' => 'nullable|numeric',
        ]);
        $item = PricelistItem::create($validated);
        return new PricelistItemResource($item);
    }

    public function show($id)
    {
        $item = PricelistItem::with('category_rel')->findOrFail($id);
        return new PricelistItemResource($item);
    }

    public function update(Request $request, $id)
    {

        $validated = $request->validate([
            'nomenklature' => 'nullable|string',
            'category' => 'nullable|exists:dict_departments,id',
            'title' => 'required|string',
            'price' => 'required|numeric',
            'costprice' => 'required|numeric',
            'archived' => 'boolean',
            'fixedsalary' => 'nullable|numeric',
            'fixedagentfee' => 'nullable|numeric',
        ]);

        $pricelistitems = PricelistItem::with('category_rel')->findOrFail($id);
        $pricelistitems->update($validated);
        $pricelistitems->refresh();  // обязательно, если нужно убедиться, что модель актуальна
        return new PricelistItemResource($pricelistitems);
    }

    public function destroy(PricelistItem $pricelistitems)
    {
        $pricelistitems->delete();
        return response()->json(['message' => 'Позиция удалена']);
    }
}
