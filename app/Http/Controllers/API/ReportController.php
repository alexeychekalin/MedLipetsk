<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ReportsController;
use App\Http\Resources\PaymentsResource;
use App\Models\Payments;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function createReport(Request $request)
    {
        $dc = new ReportsController();
        return $dc-> store($request);
    }

    public function createPaymentToReport(Request $request)
    {
        /*
        $todayDate = date('Y-m-d'); // текущая дата в формате Y-m-d
        $report = \App\Models\Report::where('date', $todayDate)->first();
        if (!$report) {
            return response()->json(['error' => 'Отчет за сегодня не найден'], 404);
        }

        $dc = new ReportsController();
        $dc-> store($request);
        */
    }

    public function deletePayment($payment_id)
    {
        $payment = Payments::find($payment_id);
        if (!$payment) response()->json(['error' => 'Платеж не найден'], 404);
        //return $payment->date;
        $paymentDate = Carbon::parse($payment->date)->toDateString();
        $report = Report::where('date_cash', $paymentDate)->first();
        if (!$report) {
            return response()->json(['error' => 'Отчет не найден'], 404);
        }
        $payment->delete();

        // Получаем остальные платежи этого отчета за тот же день
        $remainingPayments = Payments::whereDate('date', $paymentDate)
            ->get();

        $response = [
            'date' => $report->date_cash,
            'starting_cash' => $report->startingcash ?? 0,
            'payments' => PaymentsResource::collection($remainingPayments),
            //'closed' => false,
        ];
        return response()->json($response);
    }

    public function getReports(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
        ]);
        $report = Report::where('date_cash', $validated['date'])->first();

        if (!$report) {
            return response()->json(['error' => 'Отчет за этот день не найден'], 404);
        }
        $remainingPayments = Payments::whereDate('date', $validated['date'])
            ->get();

        $response = [
            'date' => $report->date_cash,
            'starting_cash' => $report->startingcash ?? 0,
            'payments' => PaymentsResource::collection($remainingPayments),
            //'closed' => false,
        ];
        return response()->json($response);
    }

    public function changePayMethod(Request $request)
    {
        $validated = $request->validate([
            'payment_id' => 'required|uuid|exists:payments,id',
            'methods' => 'required|array', // Передаете массив методов оплаты
        ]);

        $payment = Payments::findOrFail($validated['payment_id']);

        $payment->methods = $validated['methods'];
        $payment->save();

        $paymentDate = Carbon::parse($payment->date);

        $report = Report::whereDate('date_cash', $paymentDate->toDateString())->first();

        if (!$report) {
            return response()->json(['error' => 'Отчет за этот день не найден'], 404);
        }
        $payments = Payments::whereDate('date', $paymentDate->toDateString())
            ->get();

        $response = [
            'date' => $report->date_cash,
            'starting_cash' => $report->startingcash ?? 0,
            'payments' => PaymentsResource::collection($payments),
            //'closed' => false,
        ];
        return response()->json($response);

    }

    public function getReportsPeriod(Request $request)
    {
        $validated = $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:start_date',
        ]);

        $reports = Report::whereBetween('date_cash', [$validated['from'], $validated['to']])
            ->orderBy('date_cash', 'asc')
            ->get();

        if ($reports->isEmpty()) {
            return response()->json(['message' => 'Нет отчетов за указанный период'], 404);
        }

        $result = [];

        foreach ($reports as $report) {
            $payments = Payments::whereDate('date', $report->date_cash)
                ->get();

            $result[] = [
                'date' => $report->date_cash,
                'starting_cash' => $report->startingcash ?? 0,
                'payments' => PaymentsResource::collection($payments),
               // 'closed' => false, // или логика
            ];
        }

        return response()->json($result);
    }
}
