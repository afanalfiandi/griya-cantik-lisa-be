<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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
                'specialistName' => $transaction->specialistName,
                'bookingDate' => $transaction->bookingDate,
                'dateFor' => $transaction->dateFor,
                'notes' => $transaction->notes,
                'subtotal' => $transaction->subtotal,
                'service' => $services,
            ];
        });

        if ($request->has('customerID')) {
            $query->where('transaction.customerID', '=', $request->input('customerID'));
        }

        if ($request->has('paymentStatusID')) {
            $query->where('transaction.paymentStatusID', '=', $request->input('paymentStatusID'));
        }

        if ($request->has('transactionStatusID')) {
            $query->where('transaction.transactionStatusID', '=', $request->input('transactionStatusID'));
        }

        if ($request->has('paymentMethodID')) {
            $query->where('transaction.paymentMethodID', '=', $request->input('paymentMethodID'));
        }

        if ($request->has('specialistID')) {
            $query->where('transaction.specialistID', '=', $request->input('specialistID'));
        }

        if ($request->has('bookingDate')) {
            $query->where('transaction.bookingDate', '=', $request->input('bookingDate'));
        }

        if ($request->has('dateFor')) {
            $query->where('transaction.dateFor', '=', $request->input('dateFor'));
        }

        $finalResult = $groupedData->values()->toArray();

        return response()->json([
            'status' => 'success',
            'data' => $finalResult
        ]);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'customerID' => 'required|string',
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'subtotal' => 'required|integer',
            'slotID' => 'required|integer',
            'paymentMethodID' => 'required|integer',
            'specialistID' => 'required|integer',
            'bookingDate' => 'required|string',
            'dateFor' => 'required|string',
            'notes' => 'required|string',
            'service' => 'required|array',
            'service.*' => 'integer',
        ]);

        try {
            DB::beginTransaction();

            $transactionNumber = 'TR' . now()->format('YmdHis');
            $paymentStatusID = 2;
            $transactionStatusID = 1;

            $transaction = Transaction::create([
                'transactionNumber' => $transactionNumber,
                'customerID' => $validated['customerID'],
                'slotID' => $validated['slotID'],
                'paymentStatusID' => $paymentStatusID,
                'transactionStatusID' => $transactionStatusID,
                'paymentMethodID' => $validated['paymentMethodID'],
                'specialistID' => $validated['specialistID'],
                'bookingDate' => $validated['bookingDate'],
                'dateFor' => $validated['dateFor'],
                'notes' => $validated['notes'],
                'subtotal' => $validated['subtotal'],
            ]);

            if ($transaction) {
                foreach ($validated['service'] as $serviceID) {
                    DB::table('transaction_detail')->insert([
                        'transactionNumber' => $transactionNumber,
                        'serviceID' => $serviceID,
                    ]);
                }
            }

            DB::commit();

            $midtransData = [
                'payment_type' => 'bank_transfer',
                'transaction_details' => [
                    'order_id' => $transactionNumber,
                    'gross_amount' => $validated['subtotal'],
                ],
                'bank_transfer' => [
                    'bank' => 'bca',
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
                        'paymentStatusID' => 1,
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
}
