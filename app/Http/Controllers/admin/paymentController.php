<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\payment\recordPaymentRequest;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class paymentController extends Controller
{
    public function create(recordPaymentRequest $request)
    {
        try {
            $transaction = Transaction::find($request->transaction_id);

            if (!empty($transaction)) {

                if ($transaction->status == 'paid') {
                    return response()->json([
                        'success' => false,
                        'message' => 'transaction fully paid'
                    ], 200);
                } else {

                    $installments = $transaction->payments->sum('amount');

                    if ($installments >= $transaction->amount) {
                        $transaction->status = 'paid';
                        $transaction->update();

                        return response()->json([
                            'success' => false,
                            'message' => 'transaction fully paid'
                        ], 200);

                    } else {

                        $newRecord = new Payment;
                        $newRecord->transaction_id = $request->transaction_id;
                        $newRecord->amount = $request->amount;
                        $newRecord->paidOn = $request->paidOn;

                        if (!empty($request->payment_method)) {
                            $newRecord->payment_method = $request->payment_method;
                        }
                        $newRecord->details = $request->details;

                        $newRecord->save();

                        return response()->json([
                            'success' => true,
                            'message' => 'payment recorded'
                        ], 201);
                    }
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'transaction not found'
                ], 404);
            }


        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function show($transaction_id)
    {
        try {
            $transaction = Transaction::find($transaction_id);

            if (!empty($transaction)) {

                $transaction->payments->makeHidden(['created_at', 'updated_at']);

                return response()->json([
                    'success' => true,
                    'message' => $transaction->payments
                ], 201);

            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'transaction not found'
                ], 404);
            }


        } catch (\Throwable $th) {
            return response()->json(['success' => false,
                'error' => $th->getMessage()], 500);
        }
    }

}
