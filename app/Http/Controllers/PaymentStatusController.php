<?php

namespace App\Http\Controllers;

use App\Models\PaymentStatus;
use Illuminate\Http\JsonResponse;

class PaymentStatusController extends Controller
{
    public function index(): JsonResponse
    {
        $paymentStatus = PaymentStatus::all();

        return response()->json([
            'status' => 'success',
            'data' => $paymentStatus
        ], 200);
    }
}
