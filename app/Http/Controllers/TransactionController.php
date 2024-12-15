<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Midtrans\Snap;

class TransactionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = DB::table('transaction as tr')
            ->leftJoin('transaction_detail', 'tr.transactionNumber', '=', 'transaction_detail.transactionNumber')
            ->leftJoin('customers', 'tr.customerID', '=', 'customers.customerID')
            ->leftJoin('slot', 'tr.slotID', '=', 'slot.slotID')
            ->leftJoin('payment_status', 'tr.paymentStatusID', '=', 'payment_status.paymentStatusID')
            ->leftJoin('transaction_status', 'tr.transactionStatusID', '=', 'transaction_status.transactionStatusID')
            ->leftJoin('payment_method', 'tr.paymentMethodID', '=', 'payment_method.paymentMethodID')
            ->leftJoin('specialist', 'tr.specialistID', '=', 'specialist.specialistID')
            ->leftJoin('service', 'transaction_detail.serviceID', '=', 'service.serviceID')
            ->leftJoin('service_detail', 'service.serviceID', '=', 'service_detail.serviceID')
            ->select(
                'tr.transactionNumber',
                'tr.customerID',
                'customers.firstName',
                'customers.lastName',
                'tr.paymentStatusID',
                'payment_status.paymentStatusName as paymentStatus',
                'tr.transactionStatusID',
                'transaction_status.transactionStatus',
                'tr.paymentMethodID',
                'payment_method.paymentMethodName as paymentMethod',
                'tr.specialistID',
                'specialist.specialistName',
                'tr.bookingDate',
                'tr.dateFor',
                'tr.notes',
                'tr.subtotal',
                'service.serviceID',
                'service.serviceName',
                'service.price',
                'service_detail.img'
            )
            ->when($request->has('transactionNumber'), function ($query) use ($request) {
                $query->where('tr.transactionNumber', $request->input('transactionNumber'));
            })
            ->when($request->has('customerID'), function ($query) use ($request) {
                $query->where('tr.customerID', $request->input('customerID'));
            })
            ->when($request->has('paymentStatusID'), function ($query) use ($request) {
                $query->where('tr.paymentStatusID', $request->input('paymentStatusID'));
            })
            ->when($request->has('transactionStatusID'), function ($query) use ($request) {
                $query->where('tr.transactionStatusID', $request->input('transactionStatusID'));
            })
            ->when($request->has('paymentMethodID'), function ($query) use ($request) {
                $query->where('tr.paymentMethodID', $request->input('paymentMethodID'));
            })
            ->when($request->has('specialistID'), function ($query) use ($request) {
                $query->where('tr.specialistID', $request->input('specialistID'));
            })
            ->when($request->has('bookingDate'), function ($query) use ($request) {
                $query->where('tr.bookingDate', $request->input('bookingDate'));
            })
            ->when($request->has('dateFor'), function ($query) use ($request) {
                $query->where('tr.dateFor', $request->input('dateFor'));
            })
            ->get();

        $groupedData = $query->groupBy('transactionNumber')->map(function ($transactions) {
            $transaction = $transactions->first();
            $services = $transactions->map(function ($item) {
                return [
                    'serviceID' => $item->serviceID,
                    'serviceName' => $item->serviceName,
                    'price' => $item->price,
                    'img' => $item->img,
                ];
            })->values();

            return [
                'transactionNumber' => $transaction->transactionNumber,
                'customerID' => [
                    'customerID' => $transaction->customerID,
                    'firstName' => $transaction->firstName,
                    'lastName' => $transaction->lastName,
                ],
                'paymentStatus' => $transaction->paymentStatus,
                'transactionStatus' => $transaction->transactionStatus,
                'paymentMethod' => $transaction->paymentMethod,
                'time' => $transaction->time,
                'specialistName' => $transaction->specialistName,
                'bookingDate' => $transaction->bookingDate,
                'dateFor' => $transaction->dateFor,
                'notes' => $transaction->notes,
                'subtotal' => $transaction->subtotal,
                'service' => $services,
            ];
        });

        $finalResult = $groupedData->values()->toArray();

        return response()->json([
            'status' => 'success',
            'data' => $finalResult,
        ]);
    }

    public function create(Request $request)
    {
        $payload = $request->all();
        // $validated = $request->validate([
        //     'customerID' => 'required|string',
        //     'firstName' => 'required|string',
        //     'lastName' => 'required|string',
        //     'subtotal' => 'required|integer',
        //     'slotID' => 'required|integer',
        //     'paymentMethodID' => 'required|integer',
        //     'paymentType' => 'required|string',
        //     'bank' => 'required|string',
        //     'specialistID' => 'required|integer',
        //     'bookingDate' => 'required|string',
        //     'dateFor' => 'required|string',
        //     'notes' => 'required|string',
        //     'service' => 'required|array',
        //     'service.*' => 'integer',
        // ]);

        try {
            DB::beginTransaction();

            $transactionNumber = 'TR' . now()->format('YmdHis');
            $paymentStatusID = 2;
            $transactionStatusID = 1;

            $transaction = Transaction::create([
                'transactionNumber' => $transactionNumber,
                'customerID' => $payload['customerID'],
                'slotID' => $payload['slotID'],
                'paymentStatusID' => $paymentStatusID,
                'transactionStatusID' => $transactionStatusID,
                'paymentMethodID' => $payload['paymentMethodID'],
                'paymentType' => $payload['paymentType'],
                'bank' => $payload['bank'],
                'specialistID' => $payload['specialistID'],
                'bookingDate' => $payload['bookingDate'],
                'dateFor' => $payload['dateFor'],
                'notes' => $payload['notes'],
                'subtotal' => $payload['subtotal'],
            ]);

            if ($transaction) {
                foreach ($payload['service'] as $serviceID) {
                    DB::table('transaction_detail')->insert([
                        'transactionNumber' => $transactionNumber,
                        'serviceID' => $serviceID,
                    ]);
                }
            }

            DB::commit();

            $midtransData = [
                'payment_type' => $payload['paymentType'],
                'transaction_details' => [
                    'order_id' => $transactionNumber,
                    'gross_amount' => $payload['subtotal'],
                ],
                'bank_transfer' => [
                    'bank' => $payload['bank'],
                ],
            ];

            $serverKey = env('MIDTRANS_SERVER_KEY');
            $encodedKey = base64_encode($serverKey);

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . $encodedKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.sandbox.midtrans.com/v2/charge', $midtransData);


            if ($response->successful()) {
                $data = $response->json();
                $vaNumber = $data['va_numbers'][0]['va_number'];

                DB::table('transaction')
                    ->where('transactionNumber', $transactionNumber)
                    ->update([
                        'paymentStatusID' => 2,
                        'vaNumber' => $vaNumber
                    ]);

                return response()->json([
                    'message' => 'Transaction and payment processed successfully!',
                    'midtrans_response' => $response->json(),
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Payment processing failed.',
                    'error' => $response->json(),
                ], 500);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function handlePayment(Request $request)
    {

        $payload = $request->all();
        $serverKey = env('MIDTRANS_SERVER_KEY');

        Log::info('incoming-midtrans', [
            'payload' => $payload
        ]);

        $orderId = $payload['order_id'];
        $statusCode = $payload['status_code'];
        $grossAmount = $payload['gross_amount'];

        $signatureKey = $payload['signature_key'];

        $signature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signature != $signatureKey) {
            return response()->json([
                'message' => 'Invalid signature',
            ], 500);
        }

        $transactionStatus = $payload['transaction_status'];

        $order = Transaction::where('transactionNumber', $orderId)->first();

        if (!$order) {
            return response()->json([
                'message' => 'Invalid order',
            ], 400);
        }

        if ($transactionStatus == 'settlement') {
            DB::table('transaction')
                ->where('transactionNumber', $orderId)
                ->update([
                    'paymentStatusID' => 1,
                ]);
        } else if ($transactionStatus == 'expired') {
            DB::table('transaction')
                ->where('transactionNumber', $orderId)
                ->update([
                    'paymentStatusID' => 3,
                ]);
        }

        return response()->json([
            'message' => 'success'
        ], 200);
    }
}
