<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PaymentStatusController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SlotController;
use App\Http\Controllers\SpecialistController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionStatusController;
use Illuminate\Support\Facades\Route;

Route::post('/auth', [AuthController::class, 'auth']);
Route::get('/get_service_category', [ServiceCategoryController::class, 'index']);
Route::get('/get_service', [ServiceController::class, 'index']);
Route::get('/get_service/id/{serviceID}', [ServiceController::class, 'serviceByID']);
Route::get('/get_service/category/{serviceCategoryID}', [ServiceController::class, 'serviceByCategoryID']);
Route::get('/get_payment_method', [PaymentMethodController::class, 'index']);
Route::get('/get_payment_status', [PaymentStatusController::class, 'index']);
Route::get('/get_specialist', [SpecialistController::class, 'index']);
Route::get('/get_slot', [SlotController::class, 'index']);
Route::get('/get_transaction_status', [TransactionStatusController::class, 'index']);
Route::get('/get_transaction', [TransactionController::class, 'index']);
