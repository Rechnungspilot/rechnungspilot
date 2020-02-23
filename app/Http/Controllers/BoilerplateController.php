<?php

namespace App\Http\Controllers;

use App\Receipts\Boilerplate;
use Illuminate\Http\Request;

class BoilerplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Boilerplate::all();
        }

        return view('receipt.boilerplate.index')
            ->with('standards', Boilerplate::STANDARDS)
            ->with('placeholder', Boilerplate::PLACEHOLDER);
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
        $validatedData = $request->validate([
            'name' => 'required|string',
        ]);

        $validatedData['company_id'] = auth()->user()->company_id;
        $validatedData['standard'] = 0;

        return Boilerplate::create($validatedData);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Receipts\Boilerplate  $boilerplate
     * @return \Illuminate\Http\Response
     */
    public function show(Boilerplate $boilerplate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Receipts\Boilerplate  $boilerplate
     * @return \Illuminate\Http\Response
     */
    public function edit(Boilerplate $boilerplate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Receipts\Boilerplate  $boilerplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Boilerplate $boilerplate)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'standard' => 'required|numeric',
            'text' => 'string',
        ]);

        $boilerplate->update($validatedData);

        if ($request->wantsJson())
        {
            return $boilerplate;
        }

        return back()
            ->with('status', 'Änderungen gespeichert!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Receipts\Boilerplate  $boilerplate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Boilerplate $boilerplate)
    {
        $boilerplate->delete();
    }
}
