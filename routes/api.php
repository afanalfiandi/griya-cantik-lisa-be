<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MidtransController;
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
Route::get('/service_category/get', [ServiceCategoryController::class, 'index']);
Route::get('/service/get', [ServiceController::class, 'index']);
Route::get('/service/get/{serviceID}', [ServiceController::class, 'serviceByID']);
Route::get('/service/get/category/{serviceCategoryID}', [ServiceController::class, 'serviceByCategoryID']);
Route::get('/service/create', [ServiceController::class, 'create']);

Route::get('/payment_method/get', [PaymentMethodController::class, 'index']);
Route::get('/payment_status/get', [PaymentStatusController::class, 'index']);

Route::get('/specialist/get', [SpecialistController::class, 'index']);
Route::post('/specialist/create', [SpecialistController::class, 'create']);
Route::post('/specialist/update/{specialistID}', [SpecialistController::class, 'update']);
Route::delete('/specialist/delete/{specialistID}', [SpecialistController::class, 'delete']);

Route::get('/slot/get', [SlotController::class, 'index']);
Route::get('/transaction_status/get', [TransactionStatusController::class, 'index']);
Route::get('/transaction/get', [TransactionController::class, 'index']);
Route::post('/transaction/create', [TransactionController::class, 'create']);

Route::post('/customer/create', [CustomerController::class, 'store']);
Route::post('/customer/update/{customerID}', [CustomerController::class, 'update']);
Route::post('/customer/update/img/{customerID}', [CustomerController::class, 'updateImg']);
Route::delete('/customer/delete/{customerID}', [CustomerController::class, 'delete']);

Route::post('/midtrans/process', [MidtransController::class, 'create'])->name('midtrans.process');
Route::post('/midtrans/notification', [MidtransController::class, 'handleNotification']);
