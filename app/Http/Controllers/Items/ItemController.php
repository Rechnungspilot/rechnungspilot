<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
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
                ->withAllTags($request->input('tags'), Item::class)
                ->orderBy('number', 'ASC')
                ->orderBy('name', 'ASC')
                ->paginate($request->input('perPage'));
        }

        return view('item.index')
            ->with('tags', Tag::withType(Item::class)->get())
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

        return redirect($item->path);
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
    public function edit(Item $item)
    {
        $item->load([
            'customfields',
            'unit',
        ]);

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
    public function update(Request $request, Item $item)
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

        $item->customfields->validate($request)
            ->update();

        $item->setDuration($validatedData['duration_hour'], $validatedData['duration_minute']);
        $item->update($validatedData);

        return redirect($item->path)
            ->with('status', 'Änderungen gespeichert!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Item $item)
    {
        $isDeletable = $item->isDeletable();
        if ($isDeletable)
        {
            $item->prices()->delete();
            $item->delete();
        }

        if ($request->wantsJson())
        {
            return response()->json(null, 204);
        }

        return redirect($item->index_path)
            ->with('status', $isDeletable ? 'Artikel gelöscht' : 'Artikel konnte nicht gelöscht werden');
    }
}
