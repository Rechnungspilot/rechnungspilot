<?php

namespace App\Http\Controllers;

use App\Contacts\Contact;
use App\Item;
use App\Receipts\Boilerplate;
use App\Receipts\Delivery;
use App\Receipts\Statuses\Draft;
use App\Receipts\Term;
use App\Tag;
use App\Unit;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Delivery::with(['contact', 'status'])
                ->search($request->input('searchtext'))
                ->contact($request->input('contact_id'))
                ->status($request->input('status_type'))
                ->withAllTags($request->input('tags'), 'lieferscheine')
                ->orderBy('date', 'DESC')
                ->paginate($request->input('perPage'));
        }

        return view('delivery.index')
            ->with('contacts', Contact::all())
            ->with('statuses', Delivery::AVAILABLE_STATUSES)
            ->with('labels', Delivery::labels())
            ->with('tags', Tag::withType('lieferscheine')->get());
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

        $receipt = Delivery::create([
            'address' => $contact->billing_address,
            'company_id' => auth()->user()->company_id,
            'contact_id' => $contact->id,
        ]);

        if ($request->wantsJson()) {
            $receipt->cache();
            return $receipt;
        }

        return redirect($receipt->path)
            ->with('status', 'Lieferschein erstellt!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Receipts\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function show(Delivery $delivery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Receipts\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function edit(Delivery $delivery)
    {
        $delivery->load([
            'company',
            'items',
            'tags',
        ]);

        $delivery->statuses = $delivery->statuses()->with('user')->orderBy('date', 'DESC')->orderBy('id', 'DESC')->get();
        $delivery->calculateTax();

        return view('delivery.edit')
            ->with('delivery', $delivery)
            ->with('contacts', Contact::all())
            ->with('items', Item::all())
            ->with('units', Unit::all())
            ->with('boilerplates', Boilerplate::all())
            ->with('placeholders', Boilerplate::PLACEHOLDER)
            ->with('tags', Tag::withType('lieferscheine')->get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Receipts\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Delivery $delivery)
    {
        $validatedData = $request->validate([
            'address' => 'nullable|string',
            'contact_id' => 'required',
            'date' => 'date_format:d.m.Y',
            'number' => 'required|string',
            'subject' => 'nullable|string',
            'text_above' => 'nullable|string',
            'text_below' => 'nullable|string',
        ]);

        $validatedData['date'] = Carbon::createFromFormat('d.m.Y', $validatedData['date']);

        $delivery->update($validatedData);
        $delivery->cache();

        return back()
            ->with('status', 'Lieferschein gespeichert!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Receipts\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Delivery $delivery)
    {
        $delivery->statuses()->delete();
        $delivery->delete();

        if ($request->wantsJson()) {
            return;
        }

        return redirect('/lieferscheine');
    }
}
