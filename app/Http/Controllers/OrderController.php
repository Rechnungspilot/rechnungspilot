<?php

namespace App\Http\Controllers;

use App\Contacts\Contact;
use App\Item;
use App\Models\CustomFields\CustomField;
use App\Receipts\Order;
use App\Receipts\Statuses\Draft;
use App\Receipts\Term;
use App\Tag;
use App\Unit;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Order::with(['contact', 'status'])
                ->search($request->input('searchtext'))
                ->contact($request->input('contact_id'))
                ->status($request->input('status_type'))
                ->withAllTags($request->input('tags'), 'rechnungen')
                ->orderBy('created_at', 'DESC')
                ->paginate($request->input('perPage'));
        }

        return view('order.index')
            ->with('contacts', Contact::all())
            ->with('statuses', Order::AVAILABLE_STATUSES)
            ->with('labels', Order::labels())
            ->with('tags', Tag::withType('rechnungen')->get());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function raw(Request $request)
    {
        return Order::with([
                'contact',
            ])->search($request->input('searchtext'))
            ->orderBy('name', 'ASC')
            ->get();
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

        $order = Order::create([
            'address' => $contact->billingAddress,
            'company_id' => auth()->user()->company_id,
            'contact_id' => $contact->id,
        ]);

        if ($request->wantsJson()) {
            $order->cache();
            return $order;
        }

        return redirect($order->path);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Receipts\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Receipts\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        $order->load([
            'customfields',
            'expenses.status',
            'invoices.status',
            'items',
            'tags',
        ]);

        $order->statuses = $order->statuses()->with('user')->orderBy('date', 'DESC')->orderBy('id', 'DESC')->get();
        $order->calculateTax();

        return view('order.edit')
            ->with('customfields', CustomField::for($order))
            ->with('order', $order)
            ->with('units', Unit::all())
            ->with('users', User::all())
            ->with('contacts', Contact::all())
            ->with('items', Item::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Receipts\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'address' => 'nullable|string',
            'contact_id' => 'required',
            'number' => 'required|string',
        ]);

        $order->customfields->validate($request)
            ->update();

        $order->update($validatedData);
        $order->cache();

        return back()
            ->with('status', 'Auftrag gespeichert!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Receipts\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Order $order)
    {
        $order->statuses()->delete();
        $order->delete();

        if ($request->wantsJson()) {
            return;
        }

        return redirect('/auftraege');
    }
}
