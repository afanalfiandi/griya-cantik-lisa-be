<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    public function handleNotification(Request $request)
    {
        $notification = new \Midtrans\Notification();

        $transactionStatus = $notification->transaction_status;
        $orderId = $notification->order_id;

        if ($transactionStatus == 'capture') {
            // Transaksi berhasil
        } elseif ($transactionStatus == 'settlement') {
            // Transaksi selesai
        } elseif ($transactionStatus == 'pending') {
            // Transaksi tertunda
        } elseif ($transactionStatus == 'deny') {
            // Transaksi ditolak
        }

        return response()->json(['message' => 'Notification handled'], 200);
    }
}
