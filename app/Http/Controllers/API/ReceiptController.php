<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReceiptsResource;
use App\Models\MedicalServices;
use App\Models\Receipts;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function createTemplate(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'discount' => 'required|numeric|min:0',
            'medical_service' => 'required|array|exists:medical_services,id',
        ]);
        DB::beginTransaction();

        try {
            $receipt = Receipts::create([
                'total_amount' => 0,
                'discount' => $validated['discount'],
                'created_at' => now(),
            ]);

            $services = MedicalServices::whereIn('id', $validated['medical_service'])->get();

            $totalAmount = 0;
            foreach ($services as $service) {
                $totalAmount += ($service->treatment_plan_price ?? 0) * ($service->quantity ?? 1);
            }

            $receipt->update(['total_amount' => $totalAmount]);

            MedicalServices::whereIn('id', $validated['medical_service'])
                ->update(['receipt_id' => $receipt->id]);

            DB::commit();

            $receipt['medical_services'] = MedicalServices::whereIn('id', $validated['medical_service'])->get('id');;

            return response()->json($receipt, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Ошибка при создании шаблона', 'details' => $e->getMessage()], 500);
        }
    }

    public function getTemplates()
    {
        // Получаем все шаблоны чеков
        $templates = Receipts::all();

        $result = [];

        foreach ($templates as $template) {
            // Получаем связанные услуги (если нужно)
            $services = MedicalServices::where('receipt_id', $template->id)->pluck('id');

            $result[] = [
                'id' => $template->id,
                'discount' => $template->discount ?? 0,
                'total_amount' => $template->total_amount,
                'created_at' => $template->created_at,
                'medical_service' => $services,
            ];
        }

        return response()->json($result);
    }

    public function deleteTemplate($id)
    {
        DB::beginTransaction();

        try {
            // Получаем шаблон
            $template = Receipts::find($id);
            if (!$template) {
                return response()->json(['error' => 'Шаблон не найден'], 404);
            }

            // Обнуляем reference в MedicalServices, если нужно
            MedicalServices::where('receipt_id', $id)->update(['receipt_id' => null]);

            // Удаляем шаблон
            $template->delete();

            DB::commit();
            return response()->json(['message' => 'Шаблон удален успешно']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Ошибка при удалении шаблона', 'details' => $e->getMessage()], 500);
        }
    }
}
