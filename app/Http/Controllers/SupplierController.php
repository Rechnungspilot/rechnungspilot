<?php

namespace App\Http\Controllers;

use App\Contacts\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::all();

        return view('Supplier.index')
            ->with('suppliers', $suppliers);
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
        $supplier = Supplier::create([
            'company_id' => auth()->user()->id,
            'firstname' => 'Neuer',
            'lastname' => 'Lieferant',
            'number' => Supplier::nextNumber(),
        ]);

        return redirect('/lieferanten/' . $supplier->getRouteKey());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $lieferanten)
    {
        return view('Supplier.show')
            ->with('supplier', $lieferanten);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $lieferanten)
    {
        $validatedData = $request->validate([
            'address' => 'string|max:255',
            'city' => 'string|max:255',
            'company' => 'string|max:255',
            'email' => 'string||max:255',
            'firstname' => 'string|max:255',
            'lastname' => 'string|max:255',
            'number' => 'string|max:255',
            'postcode' => 'string|max:255',
        ]);

        $lieferanten->update($validatedData);

        return back()->with('status', 'Änderungen gespeichert!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $lieferanten)
    {
        $lieferanten->delete();

        return redirect('kunden')
            ->with('status', 'Kunde gelöscht!');
    }
}
