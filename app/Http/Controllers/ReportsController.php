<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Http\Resources\ReportResource;

class ReportsController extends Controller
{
    public function index()
    {
        return ReportResource::collection(Report::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'startingcash' => 'numeric',
            'date_cash' => 'required|date|unique:report,date_cash',
            'created_by' => 'nullable|exists:users,id',
        ]);
        $report = Report::create($validated);
        return new ReportResource($report);
    }

    public function show(Report $report)
    {
        return new ReportResource($report);
    }

    public function update(Request $request, Report $report)
    {
        $validated = $request->validate([
            'startingcash' => 'required|numeric',
            'date_cash' => 'required|date|unique:report,date_cash,' . $report->id,
            'created_by' => 'nullable|exists:users,id',
        ]);
        $report->update($validated);
        return new ReportResource($report);
    }

    public function destroy(Report $report)
    {
        $report->delete();
        return response()->json(['message' => 'Отчет удален']);
    }
}
