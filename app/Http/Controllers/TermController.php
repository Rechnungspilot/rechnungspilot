<?php

namespace App\Http\Controllers;

use App\Receipts\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $type)
    {
        if ($request->wantsJson()) {
            return Term::typeFromString($type)
                ->search($request->input('searchtext'))
                ->orderBy('days', 'ASC')
                ->get();
        }

        return view('receipt.term.index')
            ->with('type', $type)
            ->with('name', Term::typesName($type));
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
    public function store(Request $request, string $type)
    {
        $validatedData = $request->validate([
            'days' => 'required|numeric',
        ]);

        $validatedData['name'] = $validatedData['days'] . ' Tage';
        $validatedData['type'] = Term::getTypeFromString($type);
        $validatedData['company_id'] = auth()->user()->company_id;

        return Term::create($validatedData);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Receipts\PaymentTerm  $term
     * @return \Illuminate\Http\Response
     */
    public function show(Term $term)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Receipts\PaymentTerm  $term
     * @return \Illuminate\Http\Response
     */
    public function edit(string $type, Term $term)
    {
        return view('receipt.term.edit')
            ->with('term', $term)
            ->with('type', $type)
            ->with('name', Term::typesName($type));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Receipts\PaymentTerm  $term
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Term $term)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'days' => 'required|numeric',
            'text' => 'string',
        ]);

        $term->update($validatedData);

        return back()
            ->with('status', 'Änderungen gespeichert!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Receipts\PaymentTerm  $term
     * @return \Illuminate\Http\Response
     */
    public function destroy(Term $term)
    {
        $term->delete();
    }
}
