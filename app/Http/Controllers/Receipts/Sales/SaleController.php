<?php

namespace App\Http\Controllers\Receipts\Sales;

use App\Contacts\Contact;
use App\Http\Controllers\Controller;
use App\Item;
use App\Receipts\Invoice;
use App\Receipts\Term;
use App\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
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
                ->status('outstanding')
                ->orderBy('date', 'DESC')
                ->orderBy('number', 'DESC')
                ->paginate(15);
        }

        $sql = 'SELECT
                    item_receipt.item_id,
                    COUNT(*) AS presale_count
                FROM
                    item_receipt
                WHERE
                    item_receipt.company_id = :company_id AND
                    item_receipt.item_article_id = -1
                GROUP BY
                    item_receipt.item_id';

        $presales = DB::select($sql, [
            'company_id' => auth()->user()->company_id,
        ]);

        foreach ($presales as $key => $presale) {
            $presale->item = Item::with([
                //
            ])->find($presale->item_id);
        }

        usort($presales, function ($elem1, $elem2) {
            return strcmp($elem1->item->name, $elem2->item->name);
        });

        return view('receipts.sales.index')
            ->with('presales', $presales);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('receipts.sales.create')
            ->with('tags', Tag::withType(Contact::class)->get());
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
            'address' => $contact->billing_address,
            'company_id' => auth()->user()->company_id,
            'contact_id' => $contact->id,
            'term_id' => $term->id,
            'date_due' => now()->add($term->days, 'days'),
        ]);

        $invoice->sendWithoutMail();

        if ($request->wantsJson()) {
            $invoice->cache();
            return $invoice;
        }

        return redirect(route('receipts.sales.show', [
            'sale' => $invoice->id,
        ]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $sale)
    {
        $sale->load([
            'company',
            'items',
            'status',
        ]);

        $sale->statuses = $sale->statuses()->with('user')->orderBy('date', 'DESC')->orderBy('id', 'DESC')->get();

        return view('receipts.sales.show')
            ->with('model', $sale)
            ->with('items', \App\Item::with([
                'unit',
            ])->orderBy('name', 'ASC')->get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $sale)
    {
        $attributes = $request->validate([
            'pays_formatted' => 'required|formated_number',
            'tipp' => 'required|numeric',
        ]);

        $pays = str_replace(',', '.', $attributes['pays_formatted']) * 100;
        $amount = $pays - $attributes['tipp'];
        $amount_formatted = number_format($amount / 100, 2, ',', ',');

        $status = new \App\Receipts\Statuses\Payment();
        $status->associate($sale, [
            'date' => now()->format('d.m.Y'),
            'amount' => $amount_formatted,
            'tipp' => $attributes['tipp'],
            'account_id' => \App\Banks\Account::firstOrCreate([
                'company_id' => $sale->company_id,
                'name' => 'Bar',
            ], [])->id
        ]);

        $request->session()->flash('status', 'Bestellung erfolgreich bearbeitet.');

        return [
            'index_path' => route('receipts.sales.index'),
        ];
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

        return redirect($invoice->index_path);
    }
}
