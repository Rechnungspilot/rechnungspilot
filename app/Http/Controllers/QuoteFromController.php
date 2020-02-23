<?php

namespace App\Http\Controllers;

use App\Receipts\Quote;
use App\Receipts\Receipt;
use Illuminate\Http\Request;

class QuoteFromController extends Controller
{
    public function store(Request $request, Receipt $receipt)
    {
        $quote = Quote::from($receipt);

        return redirect($quote->path)
            ->with('status', $quote->typeName . ' erstellt');
    }
}
