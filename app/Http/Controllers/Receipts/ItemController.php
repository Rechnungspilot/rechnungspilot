<?php

namespace App\Http\Controllers\Receipts;

use App\Http\Controllers\Controller;
use App\Item;
use App\Jobs\CacheContact;
use App\Jobs\CacheItem;
use App\Receipts\Item as ReceiptItem;
use App\Receipts\Receipt;
use App\Unit;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Receipt $receipt)
    {
        if ($request->wantsJson()) {
            return $receipt->items()
                ->with([
                    'morphedItems.receipt',
                    'item',
                ])
                ->get();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Receipt $receipt)
    {
        $validatedData = $request->validate([
            'item_id' => 'required',
        ]);

        $item = Item::findOrFail($validatedData['item_id']);

        $receiptItem = $receipt->addItem($item);

        $receipt->cache();

        CacheContact::dispatch($receipt->contact);
        CacheItem::dispatch($item);

        if ($request->wantsJson()) {
            return $receiptItem->load([
                'item',
                'unit',
            ]);
        }

        return back()->with('status', 'Artikel hinzugefügt!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(ReceiptItem $receiptItem)
    {
        return view('receiptitem.edit')
            ->with('receiptitem', $receiptItem)
            ->with('units', Unit::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receipt $receipt, ReceiptItem $receiptItem)
    {
        $validatedData = $request->validate([
            'description' => 'nullable|string',
            'discount' => 'required|numeric',
            'name' => 'required',
            'quantity' => 'required|numeric',
            'tax' => 'required|numeric',
            'unit_price' => 'required|numeric',
            'unit_id' => 'required|integer'
        ]);

        $receiptItem->update($validatedData);
        $receipt->cache();

        CacheContact::dispatch($receipt->contact);
        if ($receiptItem->item_id > 0)
        {
            CacheItem::dispatch($receiptItem->item);
        }

        if ($request->wantsJson()) {
            return $receiptItem->load([
                'morphedItems.receipt',
                'item',
                'unit',
            ]);
        }

        return back()->with('status', 'Artikel gespeichert!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Receipt $receipt, ReceiptItem $receiptItem)
    {
        $receiptItem->delete();

        $receipt->cache();

        CacheContact::dispatch($receipt->contact);
        if ($receiptItem->item_id > 0)
        {
            CacheItem::dispatch($receiptItem->item);
        }

        if ($request->wantsJson())
        {
            return;
        }

        return back()->with('status', 'Artikel entfernt!');

    }
}
