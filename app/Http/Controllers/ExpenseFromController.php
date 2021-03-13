<?php

namespace App\Http\Controllers;

use App\Receipts\Expense;
use App\Receipts\Receipt;
use Illuminate\Http\Request;

class ExpenseFromController extends Controller
{
    public function store(Request $request, Receipt $receipt)
    {
        $expense = Expense::from($receipt, [
            'credit' => (bool) $request->input('credit'),
        ]);

        return redirect($expense->path)
            ->with('status', $expense->typeName . ' erstellt');
    }
}
