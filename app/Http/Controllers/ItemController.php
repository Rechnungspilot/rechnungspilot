<?php

namespace App\Http\Controllers;

use App\Item;
use App\Models\CustomFields\CustomField;
use App\Tag;
use App\Unit;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Item::with([
                'tags',
                'unit',
                ])->search($request->input('searchtext'))
                ->type($request->input('type'))
                ->withAllTags($request->input('tags'), 'artikel')
                ->orderBy('number', 'ASC')
                ->orderBy('name', 'ASC')
                ->paginate($request->input('perPage'));
        }

        return view('item.index')
            ->with('tags', Tag::withType('artikel')->get())
            ->with('types', Item::TYPES)
            ->with('units_path', Unit::indexPath());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function raw(Request $request)
    {
        return Item::with([
            'unit',
            ])->search($request->input('searchtext'))
            ->type($request->input('type') ?? -1)
            ->orderBy('number', 'ASC')
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
        $item = Item::create([
            'name' => $request->has('name') ? $request->input('name') : '',
            'unit_id' => Unit::first()->id,
            'tax' => 0.19,
        ]);

        if ($request->wantsJson()) {
            return $item->load('unit');
        }

        return redirect('/artikel/' . $item->getRouteKey());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Item::with([
            'customfields',
            'receiptItems.receipt',
            'unit',
        ])->findOrFail($id);

        return view('item.show')
            ->with('item', $item)
            ->with('quantity', 0)
            ->with('unit_price_sum', 0)
            ->with('gross', 0);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Item::with([
            'customfields',
            'unit',
        ])->findOrFail($id);

        return view('item.edit')
            ->with('item', $item)
            ->with('customfields', CustomField::for($item))
            ->with('units', Unit::all())
            ->with('types', Item::TYPES)
            ->with('decimals', Item::DECIMALS);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $artikel)
    {
        $validatedData = $request->validate([
            'description' => 'nullable|string',
            'name' => 'required|min:3|max:255',
            'number' => 'nullable|string',
            'tax' => 'required|numeric',
            'type' => 'required',
            'unit_id' => 'required|integer',
            'unit_cost' => 'required|formated_number',
            'unit_price' => 'required|formated_number',
            'expense_account_number' => 'required|numeric',
            'revenue_account_number' => 'required|numeric',
            'cost_center' => 'required|numeric',
            'duration_hour' => 'required|integer|min:0',
            'duration_minute' => 'required|integer|between:0,59',
            'decimals' => 'required|integer',
        ]);

        $artikel->customfields->validate($request)
            ->update();

        $artikel->setDuration($validatedData['duration_hour'], $validatedData['duration_minute']);
        $artikel->update($validatedData);

        return redirect($artikel->path)
            ->with('status', 'Änderungen gespeichert!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Item $artikel)
    {

        $isDeletable = $artikel->isDeletable();
        if ($isDeletable)
        {
            $artikel->prices()->delete();
            $artikel->delete();
        }

        if ($request->wantsJson())
        {
            return response()->json(null, 204);
        }

        return redirect()->route('artikel.index')
            ->with('status', $isDeletable ? 'Artikel gelöscht' : 'Artikel konnte nicht gelöscht werden');
    }
}
