<?php

namespace App\Http\Controllers;

use App\Company;
use App\Contacts\Contact;
use App\Item;
use App\Receipts\Boilerplate;
use App\Receipts\Quote;
use App\Receipts\Statuses\Accepted;
use App\Receipts\Statuses\Declined;
use App\Receipts\Statuses\Draft;
use App\Receipts\Statuses\Send;
use App\Receipts\Term;
use App\Tag;
use App\Unit;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Quote::with(['contact', 'status'])
                ->search($request->input('searchtext'))
                ->contact($request->input('contact_id'))
                ->status($request->input('status_type'))
                ->withAllTags($request->input('tags'), 'angebote')
                ->paginate(15);
        }

        return view('quote.index')
            ->with('contacts', Contact::all())
            ->with('statuses', Quote::AVAILABLE_STATUSES)
            ->with('labels', Quote::labels())
            ->with('tags', Tag::withType('angebote')->get());
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

        $term = Term::default(Quote::class);

        $quote = Quote::create([
            'address' => $contact->billingAddress,
            'company_id' => auth()->user()->company_id,
            'contact_id' => $contact->id,
            'term_id' => $term->id,
            'date_due' => now()->add($term->days, 'days'),
        ]);

        if ($request->wantsJson()) {
            $quote->cache();
            return $quote;
        }

        return redirect($quote->path);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $quote = Quote::with([
            'items',
            'tags',
        ])->findOrFail($id);

        $quote->statuses = $quote->statuses()->with('user')->orderBy('date', 'DESC')->orderBy('id', 'DESC')->get();

        return view('quote.show')
            ->with('quote', $quote);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $quote = Quote::with([
            'items',
            'tags',
            'term',
        ])->findOrFail($id);
        $quote->statuses = $quote->statuses()->with('user')->orderBy('date', 'DESC')->orderBy('id', 'DESC')->get();

        $quote->calculateTax();

        return view('quote.edit')
            ->with('quote', $quote)
            ->with('contacts', Contact::all())
            ->with('items', Item::all())
            ->with('units', Unit::all())
            ->with('users', User::all())
            ->with('boilerplates', Boilerplate::all())
            ->with('placeholders', Boilerplate::PLACEHOLDER)
            ->with('tags', Tag::withType('angebote')->get())
            ->with('terms', Term::where('type', Quote::class)
                ->orderBy('name', 'ASC')
                ->get()
                ->keyBy('id')
            );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quote $quote)
    {
        $validatedData = $request->validate([
            'address' => 'nullable|string',
            'contact_id' => 'required',
            'date' => 'date_format:d.m.Y',
            'term_id' => 'required',
            'date_due' => 'date_format:d.m.Y',
            'number' => 'required|string',
            'subject' => 'nullable|string',
            'text_above' => 'nullable|string',
            'text_below' => 'nullable|string',
        ]);

        $validatedData['date'] = Carbon::createFromFormat('d.m.Y', $validatedData['date'])->startOfDay();
        $validatedData['date_due'] = Carbon::createFromFormat('d.m.Y', $validatedData['date_due'])->startOfDay();

        $quote->update($validatedData);
        $quote->cache();

        return back()
            ->with('status', 'Angebot gespeichert!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Quote $quote)
    {
        $quote->statuses()->delete();
        $quote->delete();

        if ($request->wantsJson()) {
            return;
        }

        return redirect(route('receipt.quote.index'));
    }
}
