<?php

namespace App\Http\Controllers\Receipts\Items;

use App\Http\Controllers\Controller;
use App\Item;
use App\Jobs\CacheContact;
use App\Jobs\CacheItem;
use App\Receipts\Abos\Abo;
use App\Receipts\Item as LineItem;
use App\Receipts\Receipt;
use App\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

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
        $attributes = $request->validate([
            'item_id' => 'required',
            'item_article_id' =>'sometimes|integer',
        ]);

        $item = Item::findOrFail($attributes['item_id']);

        $line_item = $receipt->addItem($item, $attributes);

        $receipt->cache();

        $this->cacheContact($receipt);
        CacheItem::dispatch($item);

        if ($request->wantsJson()) {
            $line_item->should_edit = ! Arr::has($attributes, 'item_article_id');
            return $line_item->load([
                'morphedItems.receipt',
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
    public function edit(LineItem $item)
    {
        return view('receiptitem.edit')
            ->with('receiptitem', $item)
            ->with('units', Unit::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receipt $receipt, LineItem $item)
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

        $item->update($validatedData);
        $receipt->cache();

        $this->cacheContact($receipt);
        if ($item->item_id > 0) {
            CacheItem::dispatch($item->item);
        }

        if ($request->wantsJson()) {
            return $item->load([
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
    public function destroy(Request $request, Receipt $receipt, LineItem $item)
    {
        $item->delete();

        $receipt->cache();

        $this->cacheContact($receipt);
        if ($item->item_id > 0)
        {
            CacheItem::dispatch($item->item);
        }

        if ($request->wantsJson())
        {
            return;
        }

        return back()->with('status', 'Artikel entfernt!');

    }

    protected function cacheContact(Receipt $receipt) : void
    {
        if ($receipt->contact_id) {
            CacheContact::dispatch($receipt->contact);
        }
        elseif ($receipt->type == Abo::class) {
            foreach ($receipt->contacts as $key => $contact) {
                CacheContact::dispatch($contact);
            }
        }
    }
}
