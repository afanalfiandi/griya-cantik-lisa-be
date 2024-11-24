<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\JsonResponse;

class PaymentMethodController extends Controller
{
    public function index(): JsonResponse
    {
        $paymentMethod = PaymentMethod::all();

        return response()->json([
            'status' => 'success',
            'data' => $paymentMethod
        ], 200);
    }
}
