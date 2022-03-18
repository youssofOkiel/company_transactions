<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class transactionController extends Controller
{
    public function index()
    {
        try {
            $transactions = Transaction::where('payer', Auth::user()->id)->get();

            return response()->json([
                'success' => true,
                'data' =>  $transactions
            ],  200);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage()
            ], 500);
        }
    }

}
