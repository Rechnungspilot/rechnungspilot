<?php

namespace App\Http\Controllers;

use App\Receipts\Income;
use App\Receipts\Receipt;
use Illuminate\Http\Request;

class IncomeFromController extends Controller
{
    public function store(Request $request, Receipt $receipt)
    {
        $income = Income::from($receipt, (bool) $request->input('credit'));

        return redirect($income->path)
            ->with('status', $income->typeName . ' erstellt');
    }
}
