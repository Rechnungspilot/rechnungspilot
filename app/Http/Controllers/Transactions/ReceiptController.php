<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Receipts\Expense;
use App\Receipts\Invoice;
use App\Receipts\Receipt;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Receipt::with(['contact'])
                ->where('outstanding', '>', 0)
                ->whereIn('type', [
                    Invoice::class,
                    Expense::class,
                ])
                ->orderBy('date', 'DESC')
                ->get();
        }
    }
}
