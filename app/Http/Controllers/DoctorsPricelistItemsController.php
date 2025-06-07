<?php

namespace App\Http\Controllers;

use App\Models\DoctorsPricelistItems;
use Illuminate\Http\Request;
use App\Http\Resources\DoctorsPricelistItemsResource;

class DoctorsPricelistItemsController extends Controller
{
    public function index()
    {
        return DoctorsPricelistItemsResource::collection(DoctorsPricelistItems::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_doctor' => 'required|exists:doctors,id',
            'id_pricelist_item' => 'required|exists:pricelist_items,id',
            'is_basic' => 'boolean',
        ]);
        $relation = DoctorsPricelistItems::create($validated);
        return new DoctorsPricelistItemsResource($relation);
    }

    public function show($id)
    {
        return  DoctorsPricelistItemsResource::collection(DoctorsPricelistItems::where('id_doctor', $id)->get());
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_doctor' => 'required|exists:doctors,id',
            'id_pricelist_item' => 'required|exists:pricelist_items,id',
            'is_basic' => 'boolean',
        ]);
        $item = DoctorsPricelistItems::where('id_doctor', $id)->first();
        $item->update($validated);
        $item->refresh();  // обязательно, если нужно убедиться, что модель актуальна
        return new DoctorsPricelistItemsResource($item);
    }

    public function destroy(DoctorsPricelistItems $relation)
    {
        $relation->delete();
        return response()->json(['message' => 'Связь удалена']);
    }
}
