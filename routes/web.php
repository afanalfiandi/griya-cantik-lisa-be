<?php

use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/service/{serviceID}/view-images', [ServiceController::class, 'viewImages'])->name('service.view_images');
