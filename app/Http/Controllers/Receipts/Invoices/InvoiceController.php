<?php

namespace App\Http\Controllers\Receipts\Invoices;

use App\Company;
use App\Contacts\Contact;
use App\Http\Controllers\Controller;
use App\Item;
use App\Jobs\CacheContact;
use App\Receipts\Boilerplate;
use App\Receipts\Invoice;
use App\Receipts\Statuses\Draft;
use App\Receipts\Term;
use App\Tag;
use App\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Invoice::with(['contact', 'status'])
                ->search($request->input('searchtext'))
                ->contact($request->input('contact_id'))
                ->status($request->input('status_type'))
                ->withAllTags($request->input('tags'), 'rechnungen')
                ->orderBy('date', 'DESC')
                ->orderBy('number', 'DESC')
                ->paginate(15);
        }

        return view('invoice.index')
            ->with('contacts', Contact::all())
            ->with('statuses', Invoice::AVAILABLE_STATUSES)
            ->with('labels', Invoice::labels())
            ->with('tags', Tag::withType('rechnungen')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $contact = $request->has('contact_id') ? Contact::find($request->input('contact_id')) : Contact::first();

        $term = Term::default(Invoice::class, $contact->invoice_term_id);

        $invoice = Invoice::create([
            'address' => $contact->billingAddress,
            'company_id' => auth()->user()->company_id,
            'contact_id' => $contact->id,
            'term_id' => $term->id,
            'date_due' => now()->add($term->days, 'days'),
        ]);

        if ($request->wantsJson()) {
            $invoice->cache();
            return $invoice;
        }

        return redirect($invoice->path);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        $invoice->load([
            'duns.settings.level',
            'finalinvoice',
            'items',
            'partialinvoices',
            'tags',
            'status',
        ]);

        $invoice->statuses = $invoice->statuses()->with('user')->orderBy('date', 'DESC')->orderBy('id', 'DESC')->get();

        return view('invoice.show')
            ->with('invoice', $invoice);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        $invoice->load([
            'finalinvoice',
            'order',
            'partialinvoices',
            'tags',
            'term',
        ]);

        $invoice->statuses = $invoice->statuses()->with('user')->orderBy('date', 'DESC')->orderBy('id', 'DESC')->get();

        return view('invoice.edit')
            ->with('invoice', $invoice)
            ->with('contacts', Contact::all())
            ->with('units', Unit::all())
            ->with('items', Item::all())
            ->with('boilerplates', Boilerplate::all())
            ->with('placeholders', Boilerplate::PLACEHOLDER)
            ->with('terms', Term::where('type', Invoice::class)
                ->orderBy('name', 'ASC')
                ->get()
                ->keyBy('id')
            );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validatedData = $request->validate([
            'address' => 'nullable|string',
            'contact_id' => 'required',
            'date' => 'date_format:d.m.Y',
            'date_due' => 'date_format:d.m.Y',
            'number' => 'required|integer',
            'term_id' => 'required',
            'text_above' => 'nullable|string',
            'text_below' => 'nullable|string',
            'final_invoice_id' => 'nullable|numeric',
            'is_partial' => 'nullable|boolean',
        ]);

        $validatedData['date'] = Carbon::createFromFormat('d.m.Y', $validatedData['date'])->startOfDay();
        $validatedData['date_due'] = Carbon::createFromFormat('d.m.Y', $validatedData['date_due'])->startOfDay();

        $oldContactId = $invoice->contact_id;

        $invoice->update($validatedData);
        $invoice->cache();

        if ($validatedData['contact_id'] != $oldContactId)
        {
            CacheContact::dispatch(Contact::find($oldContactId));
            CacheContact::dispatch(Contact::find($validatedData['contact_id']));
        }

        return back()->with('status', 'Rechnung gespeichert!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Invoice $invoice)
    {
        $invoice->statuses()->delete();
        $invoice->delete();

        if ($request->wantsJson()) {
            return;
        }

        return redirect(route('receipt.invoice.index'));
    }
}
