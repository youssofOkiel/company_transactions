<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\report\basicReportRequest;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class reportController extends Controller
{
    public function createBasicReport(basicReportRequest $request)
    {
        try {

            $transactions = Transaction::select('id', 'dueOn', 'status', 'amount')->with('payments')
                ->whereBetween(DB::raw("(DATE_FORMAT(dueOn,'%Y-%m-%d'))"), [$request->start_date, $request->end_date])
                ->get();

            foreach ($transactions as $transaction) {

                $transaction["period"] = date('Y-m', strtotime($transaction->dueOn));
                $transaction['paid_amount'] = $transaction->payments->sum('amount');
                $transaction['outStanding_amount'] = $transaction->amount - $transaction->payments->sum('amount');

                if ($transaction->status == 'overDue') {
                    if (count($transaction->payments) > 0) {
                        $transaction['overDue_amount'] = (($transaction->amount - $transaction->payments->sum('amount')) / $transaction->amount);
                    } else {
                        $transaction['overDue_amount'] = 100;
                    }
                } else {
                    $transaction['overDue_amount'] = 0.0;
                }

            }

            $transactions->makeHidden(['payments', 'status', 'amount', 'dueOn']);
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

    public function createMonthlyReport()
    {
        try {
            $current_date = Carbon::parse(Carbon::now())->format('m');

            $monthes_of_transactions = DB::table('transactions')
                ->leftJoin('payments', 'transactions.id', '=', 'payments.transaction_id')
                ->selectRaw('month(transactions.dueOn) as month, year(transactions.dueOn) as year')
                ->groupBy('month', 'year')
                ->having('month', '>=', $current_date)
                ->get();

            $transactions = Transaction::with('payments')
                ->where(DB::raw("(DATE_FORMAT(dueOn,'%m'))"), '>=', $current_date)
                ->get();

            foreach ($monthes_of_transactions as $monthes_of_transaction) {

                $paid = 0;
                $outstanding = 0;
                $overdue = 0;

                foreach ($transactions as $transaction) {
                    if (date('m', strtotime($transaction["dueOn"])) == $monthes_of_transaction->month) {
                        $paid += $transaction->payments->sum('amount');
                        $outstanding += $transaction->amount - $transaction->payments->sum('amount');

                        if ($transaction["status"] == 'overDue') {
                            $overdue += (($transaction["amount"] - $transaction->payments->sum('amount')) / $transaction["amount"]);
                        }
                    }
                }
                $monthes_of_transaction->paid = $paid;
                $monthes_of_transaction->outStanding = $outstanding;
                $monthes_of_transaction->overdue = $overdue;
            }

//            $transactions->makeHidden([ 'payments','status', 'amount' , 'dueOn']);
            return response()->json([
                'success' => true,
                'data' => $monthes_of_transactions,

            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage()
            ], 500);
        }
    }

}
