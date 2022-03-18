<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\transaction\createTransactionRequest;
use App\Models\Transaction;
use Illuminate\Support\Carbon;

class transactionController extends Controller
{

    public function create(createTransactionRequest $request)
    {
        try {

            Transaction::create([
                'category_id' => $request->category_id,
                'subCategory_id' => $request->subCategory_id,
                'amount' => $request->amount,
                'payer' => $request->payer,
                'dueOn' => $request->dueOn,
                'VAT' => $request->VAT,
                'is_VAT_inclusive' => $request->is_VAT_inclusive,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'ok'
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        try {
            $transactions = Transaction::all();

            $this->transactionStatus($transactions);

            return response()->json([
                'success' => true,
                'data' => $transactions
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function transactionStatus($transactions)
    {
        foreach ($transactions as $transaction) {

            $transaction_overDuo_date = explode(' ', $transaction->dueOn);
            $current_date = Carbon::parse(Carbon::now())->format('Y-m-d');

            // check paid
            if ($transaction->status != 'paid') {
                // check dates
                if ($transaction_overDuo_date[0] == $current_date) {
                    $transaction->status = 'overDue';
                }

                if ($transaction_overDuo_date[0] < $current_date) {
                    $transaction->status = 'overDue';
                }

                $transaction->update();
            }

        }
    }

}
