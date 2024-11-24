<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PaymentStatusController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SpecialistController;
use Illuminate\Support\Facades\Route;

Route::post('/auth', [AuthController::class, 'auth']);
Route::get('/get_service_category', [ServiceCategoryController::class, 'index']);
Route::get('/get_service', [ServiceController::class, 'index']);
Route::get('/get_service/id/{serviceID}', [ServiceController::class, 'serviceByID']);
Route::get('/get_service/category/{serviceCategoryID}', [ServiceController::class, 'serviceByCategoryID']);
Route::get('/get_payment_method', [PaymentMethodController::class, 'index']);
Route::get('/get_payment_status', [PaymentStatusController::class, 'index']);
Route::get('/get_specialist', [SpecialistController::class, 'index']);
