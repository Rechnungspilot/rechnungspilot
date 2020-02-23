<?php

namespace App\Http\Controllers;

use App\Receipts\Receipt;
use App\Receipts\Invoice;
use Illuminate\Http\Request;

class InvoiceFromController extends Controller
{
    public function store(Request $request, Receipt $receipt)
    {
        $invoice = Invoice::from($receipt, [
            'credit' => (bool) $request->input('credit'),
            'receipt_item_ids' => $request->input('receipt_item_ids'),
        ]);

        if ($request->wantsJson())
        {
            return $invoice;
        }

        return redirect($invoice->path)
            ->with('status', $invoice->typeName . ' erstellt');
    }
}