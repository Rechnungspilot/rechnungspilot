<?php

namespace App\Http\Controllers\Receipts\Invoices;

use App\Contacts\Contact;
use App\Http\Controllers\Controller;
use App\Item;
use App\Receipts\Invoice;
use App\Receipts\Term;
use Illuminate\Http\Request;

class KeepsevenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('invoice.keepseven.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'quantity' => 'required|formated_number',
        ]);

        $attributes['quantity'] = str_replace(',', '.', $attributes['quantity']);

        $contact = Contact::find(42);
        $term = Term::default(Invoice::class, $contact->invoice_term_id);
        $item = Item::find(14);

        $invoice = Invoice::create([
            'address' => $contact->billingAddress,
            'company_id' => 1,
            'contact_id' => $contact->id,
            'term_id' => $term->id,
            'date_due' => now()->add($term->days, 'days'),
        ]);

        $invoice->addItem($item, $attributes);

        $invoice->cache();

        $invoice->send();
        $invoice->send(config('app.email'), 'Rechnug für KeepSeven');

        return back()
            ->with('status', 'Rechnung erstellt und versendet!');
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
