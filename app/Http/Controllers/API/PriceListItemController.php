<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PricelistItemResource;
use App\Models\PricelistItem;
use Illuminate\Http\Request;

class PriceListItemController extends Controller
{
    public function createPLI(Request $request)
    {
        $dc = new \App\Http\Controllers\PricelistItemController();
        return $dc-> store($request);
    }

    public function updatePLI(Request $request, $id)
    {
        $dc = new \App\Http\Controllers\PricelistItemController();
        return $dc-> update($request, $id);
    }

    public function findCodePLI(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
        ]);

        $item = PricelistItem::where('nomenklature', $validated['code'])->first();

        if (!$item) {
            return response()->json(['error' => 'Позиция не найдена'], 404);
        }
        return new PricelistItemResource($item);
    }

    public function findCategoryPLI(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|exists:dict_departments,id',
        ]);
        $items = PricelistItem::where('category', $validated['category'])
            ->where('archived', false)
            ->get();
        return PricelistItemResource::collection($items);
    }

    public function archivedPLI(Request $request)
    {
        $items = PricelistItem::where('archived', true)
            ->get();
        return PricelistItemResource::collection($items);
    }

    public function findPLI(Request $request)
    {
        $validated = $request->validate([
            'search' => 'required|string',
        ]);

        $term = $validated['search'];
        // Предполагается, что есть поля 'name' и 'code'
        $items = PricelistItem::query()
            ->where(function($q) use ($term) {
                $q->where('title', 'ilike', '%' . $term . '%')
                    ->orWhere('nomenklature', 'ilike', '%' . $term . '%');
            })
            ->get();
        return PricelistItemResource::collection($items);
    }
}
