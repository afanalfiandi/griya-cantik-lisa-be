<?php

namespace App\Http\Controllers;

use App\Models\TransactionStatus;
use Illuminate\Http\JsonResponse;

class TransactionStatusController extends Controller
{
    public function index(): JsonResponse
    {
        $paymentStatus = TransactionStatus::all();

        return response()->json([
            'status' => 'success',
            'data' => $paymentStatus
        ], 200);
    }
}
