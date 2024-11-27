<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->all();

        $params = [
            'transaction_details' => [
                'order_id' => $data['order_id'],
                'gross_amount' => $data['gross_amount'],
            ],
            'customer_details' => [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            return response()->json([
                'snap_token' => $snapToken,
                'message'    => 'Payment processed successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to process payment',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
