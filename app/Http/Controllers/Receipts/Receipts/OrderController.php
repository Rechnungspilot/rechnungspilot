<?php

namespace App\Http\Controllers\Receipts\Receipts;

use App\Http\Controllers\Controller;
use App\Receipts\Receipt;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receipt $receipt)
    {
        $validatedData = $request->validate([
            'receipt_id' => 'nullable|numeric',
        ]);

        $receipt->update($validatedData);
    }
}
