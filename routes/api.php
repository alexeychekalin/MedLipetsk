<?php

use App\Http\Controllers\AccessLevelController;
use App\Http\Controllers\API\DoctorController;
use App\Http\Controllers\API\PatientController;
use App\Http\Controllers\API\ReceiptController;
use App\Http\Controllers\API\ReportController;
use App\Http\Controllers\API\ScheduleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckTemplatesController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DoctorSchedulesController;
use App\Http\Controllers\DoctorsController;
use App\Http\Controllers\DoctorsPricelistItemsController;
use App\Http\Controllers\DoctorsSpecializationsController;
use App\Http\Controllers\MedicalServicesController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\PassportsController;
use App\Http\Controllers\PatientAppointmentsController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\PaymentPurposeController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\PricelistItemController;
use App\Http\Controllers\PricelistItemSnapshotController;
use App\Http\Controllers\PricelistItemsTreatmentPlansController;
use App\Http\Controllers\ReceiptsController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SalariesController;
use App\Http\Controllers\SalariesSnapshotsController;
use App\Http\Controllers\SpecializationController;
use App\Http\Controllers\TreatmentPlansController;
use App\Http\Controllers\UsersAccessLevelController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'store']);
Route::post('/call_events', [AuthController::class, 'call_events']);
Route::middleware('auth:sanctum')->group(function (){
    Route::get('/user', function (Request $request){
        return $request->user();
    });
    Route::get('/us', [AuthController::class, 'allusers']);
});

Route::middleware('auth:sanctum')->group(function (){
    Route::apiResource('departments', DepartmentController::class);
    Route::apiResource('specializations', SpecializationController::class);
    Route::apiResource('payment-purposes', PaymentPurposeController::class);
    Route::apiResource('pricelistitems', PricelistItemController::class);
    Route::apiResource('pricelist-snapshots', PricelistItemSnapshotController::class);
    Route::apiResource('passports', PassportsController::class);
    Route::apiResource('users', UsersController::class);
    Route::apiResource('access-level', AccessLevelController::class);
    Route::apiResource('users-access-level', UsersAccessLevelController::class);
    Route::apiResource('patients', PatientsController::class);
    Route::apiResource('receipts', ReceiptsController::class);
    Route::apiResource('treatment-plans', TreatmentPlansController::class);
    Route::apiResource('doctors', DoctorsController::class);
    Route::apiResource('salaries', SalariesController::class);
    Route::apiResource('salaries-snapshot', SalariesSnapshotsController::class);
    Route::apiResource('doctor-schedules', DoctorSchedulesController::class);
    Route::apiResource('patient-appointments', PatientAppointmentsController::class);
    Route::apiResource('payments', PaymentsController::class);
    Route::apiResource('doctors-pricelist-items', DoctorsPricelistItemsController::class);
    Route::apiResource('doctors-specializations', DoctorsSpecializationsController::class);
    Route::apiResource('pricelist-items-treatment-plans', PricelistItemsTreatmentPlansController::class);
    Route::apiResource('medical-services', MedicalServicesController::class);
    Route::apiResource('check-templates', CheckTemplatesController::class);
    Route::apiResource('notes', NotesController::class);
    Route::apiResource('report', ReportsController::class);
});

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('/schedule', [ScheduleController::class, 'schedulesByDay']);
    Route::post('/schedule', [ScheduleController::class, 'createSchedule']);
    Route::put('/schedule/patient/register', [ScheduleController::class, 'registerPatient']);
    Route::put('/schedule/patient/register/new', [ScheduleController::class, 'registerPatientNew']);
    Route::put('/schedule/patient/cancel/', [ScheduleController::class, 'cancelPatientAppointment']);
    Route::put('/schedule/bounds', [ScheduleController::class, 'Bounds']);
    Route::post('/schedule/note', [ScheduleController::class, 'createNoteSchedule']);
    Route::put('/schedule/note', [ScheduleController::class, 'updateNoteSchedule']);
    Route::post('/schedule/note/appointment', [ScheduleController::class, 'createNoteAppointment']);
    Route::put('/schedule/note/appointment', [ScheduleController::class, 'updateNoteAppointment']);

    Route::post('/doctor/', [DoctorController::class, 'createDoctor']);
    Route::get('/doctor/find', [DoctorController::class, 'findDoctor']);
    Route::put('/doctor/{doctor}', [DoctorController::class, 'updateDoctor']);
    Route::get('/doctor/', [DoctorController::class, 'getDoctor']);
    Route::get('/doctor/{doctor_id}', [DoctorController::class, 'getOneDoctor'])->where('doctor_id', '[0-9a-fA-F-]{36}');;
    Route::delete('/doctor/{doctor_id}', [DoctorController::class, 'deleteDoctor']);
    Route::get('/doctor/transactions/{id}', [DoctorController::class, 'paymentsDoctor']);

    Route::post('/patient/', [PatientController::class, 'createPatient']);
    Route::get('/patient/find', [PatientController::class, 'findPatient']);
    Route::put('/patient/{patient}', [PatientController::class, 'updatePatient']);
    Route::get('/patient/', [PatientController::class, 'getPatient']);
    Route::get('/patient/{patient_id}', [PatientController::class, 'getOnePatient'])->where('patient_id', '[0-9a-fA-F-]{36}');
    Route::delete('/patient/{patient_id}', [PatientController::class, 'deletePatient']);
    Route::get('/patient/last', [PatientController::class, 'lastPatient']);
    Route::get('/patient/plan', [PatientController::class, 'planPatient']);
    Route::get('/patient/balance', [PatientController::class, 'balancePatient']);
    Route::get('/patient/transactions/{id}', [PatientController::class, 'paymentsPatient']);

    Route::post('/receipt/templates', [ReceiptController::class, 'createTemplate']);
    Route::get('/receipt/templates', [ReceiptController::class, 'getTemplates']);
    Route::delete('/receipt/templates/{id}', [ReceiptController::class, 'deleteTemplate']);

    Route::post('/report', [ReportController::class, 'createReport']);
    // Route::post('/report', [ReportController::class, 'createPaymentToReport']);
    Route::delete('/report/payment/cancel/{payment_id}', [ReportController::class, 'cancelPayment']);
    Route::get('/report/day', [ReportController::class, 'getReports']);
    Route::get('/report/period', [ReportController::class, 'getReportsPeriod']);
    Route::patch('/report/payment/update', [ReportController::class, 'changePayMethod']);
    Route::post('/report/payment/make/medical/{patient_id}', [ReportController::class, 'makeMedical']);
    Route::post('/report/payment/make/payout/{doctor_id}', [ReportController::class, 'makePayout']);
    Route::post('/report/payment/make/balance/{id}', [ReportController::class, 'makeBalancePayment']);
    Route::post('/report/payment/make', [ReportController::class, 'makePayment']);


    Route::post('/pricelistitem', [\App\Http\Controllers\API\PriceListItemController::class, 'createPLI']);
    Route::put('/pricelistitem/{pli}', [\App\Http\Controllers\API\PriceListItemController::class, 'updatePLI']);
    Route::get('/pricelistitem', [\App\Http\Controllers\API\PriceListItemController::class, 'findCodePLI']);
    Route::get('/pricelistitem/category', [\App\Http\Controllers\API\PriceListItemController::class, 'findCategoryPLI']);
    Route::get('/pricelistitem/archived', [\App\Http\Controllers\API\PriceListItemController::class, 'archivedPLI']);
    Route::get('/pricelistitem/find', [\App\Http\Controllers\API\PriceListItemController::class, 'findPLI']);
});
