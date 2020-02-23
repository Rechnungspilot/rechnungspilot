<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Item;
use App\Receipts\Duns\Level;
use Illuminate\Http\Request;

class DunningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('settings.dunning.index')
            ->with('levels', Level::with(['item'])->orderBy('level', 'ASC')->get());
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
        $nextLevel = Level::nextNumber();

        $level = Level::create([
            'level' => $nextLevel,
            'company_id' => auth()->user()->company_id,
            'name' => $nextLevel . '. Mahnung',
        ]);

        return redirect($level->path . '/edit')
            ->with('status', 'Mahnstufe erstellt!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  Level $level
     * @return \Illuminate\Http\Response
     */
    public function show(Level $level)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  Level $level
     * @return \Illuminate\Http\Response
     */
    public function edit(Level $level)
    {
        return view('settings.dunning.edit')
            ->with('level', $level)
            ->with('items', Item::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  Level $level
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Level $level)
    {
        $validatedData = $request->validate([
            'level' => 'required|numeric',
            'item_id' => 'required',
            'amount' => 'required',
        ]);

        $level->update($validatedData);

        return back()
            ->with('status', 'Mahnstufe gespeichert!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  Level $level
     * @return \Illuminate\Http\Response
     */
    public function destroy(Level $level)
    {
        //
    }
}
