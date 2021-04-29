<?php

namespace App\Http\Controllers\Api\Receipts;

use App\Company;
use App\Contacts\Contact;
use App\Http\Controllers\Controller;
use App\Item;
use App\Jobs\CacheContact;
use App\Jobs\CacheItem;
use App\Receipts\Invoice;
use App\Receipts\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Company $company)
    {
        return $company->invoices()
            ->with([
                'contact',
                'items'
            ])
            ->hasItemId($request->input('item_id'))
            ->paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Company $company)
    {
        $attributes = $request->validate([
            'receipt' => 'nullable|array',
            'contact' => 'required|array',
            'items' => 'required|array',
        ]);

        $contact = ($request->has('contact.id') && ! is_null($request->input('contact.id'))) ? Contact::find($request->input('contact.id')) : Contact::create($attributes['contact'])->refresh();
        $term = Term::default(Invoice::class, $contact->invoice_term_id);

        $invoice = Invoice::create([
            'address' => $contact->billing_address,
            'company_id' => $company->id,
            'contact_id' => $contact->id,
            'term_id' => $term->id,
            'date_due' => now()->add($term->days, 'days'),
        ]);

        foreach ($attributes['items'] as $key => $item_attributes) {
            $item = Item::find($item_attributes['id']);
            if (Arr::has($item_attributes, 'gross')) {
                $item_attributes['unit_price'] = ($item_attributes['gross'] / (1 + $item->tax)) / $item_attributes['quantity'];
            }
            $line_item = $invoice->addItem($item, $item_attributes);
            CacheItem::dispatch($item);
        }

        $invoice->load([
            'items',
        ])->cache();

        CacheContact::dispatch($contact);

        return $invoice;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
