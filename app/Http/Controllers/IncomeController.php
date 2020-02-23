<?php

namespace App\Http\Controllers;

use App\Contacts\Contact;
use App\Item;
use App\Jobs\CacheContact;
use App\Receipts\Boilerplate;
use App\Receipts\Income;
use App\Receipts\Statuses\Created;
use App\Receipts\Statuses\Draft;
use App\Receipts\Term;
use App\Tag;
use App\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Income::with(['contact', 'status'])
                ->search($request->input('searchtext'))
                ->contact($request->input('contact_id'))
                ->status($request->input('status_type'))
                ->withAllTags($request->input('tags'), 'einnahmen')
                ->orderBy('date', 'DESC')
                ->paginate(15);
        }

        return view('income.index')
            ->with('contacts', Contact::all())
            ->with('statuses', Income::AVAILABLE_STATUSES)
            ->with('labels', Income::labels())
            ->with('tags', Tag::withType('einnahmen')->get());
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

        $receipt = Income::create([
            'address' => $contact->billingAddress,
            'company_id' => auth()->user()->company_id,
            'contact_id' => $contact->id,
            'name' => '',
        ]);

        if ($request->wantsJson()) {
            $receipt->cache();
            return $receipt;
        }

        return redirect($receipt->path);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Receipts\income  $income
     * @return \Illuminate\Http\Response
     */
    public function show(Income $income)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Receipts\income  $income
     * @return \Illuminate\Http\Response
     */
    public function edit(Income $income)
    {
        $income->load([
            'items',
            'tags',
            'previewFile',
        ]);

        $income->statuses = $income->statuses()->with('user')->orderBy('date', 'DESC')->orderBy('id', 'DESC')->get();
        $income->calculateTax();

        return view('income.edit')
            ->with('income', $income)
            ->with('contacts', Contact::all())
            ->with('units', Unit::all())
            ->with('items', Item::all())
            ->with('boilerplates', Boilerplate::all())
            ->with('placeholders', Boilerplate::PLACEHOLDER)
            ->with('terms', Term::where('type', Income::class)
                ->orderBy('name', 'ASC')
                ->get()
                ->keyBy('id')
            );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Receipts\income  $income
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Income $income)
    {
        $validatedData = $request->validate([
            'contact_id' => 'required',
            'date' => 'date_format:d.m.Y',
            'name' => 'nullable|string',
        ]);

        $validatedData['date'] = Carbon::createFromFormat('d.m.Y', $validatedData['date']);

        $oldContactId = $income->contact_id;

        $income->update($validatedData);
        $income->cache();

        if ($validatedData['contact_id'] != $oldContactId)
        {
            CacheContact::dispatch(Contact::find($oldContactId));
            CacheContact::dispatch(Contact::find($validatedData['contact_id']));
        }

        return back()
            ->with('status', 'Ausgabe gespeichert!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Receipts\income  $income
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Income $income)
    {
        $income->statuses()->delete();
        $income->delete();

        if ($request->wantsJson()) {
            return;
        }

        return redirect('/einnahmen');
    }
}
