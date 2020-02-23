<?php

namespace App\Http\Controllers;

use App\Contacts\Contact;
use App\Item;
use App\Receipts\Boilerplate;
use App\Receipts\Dun;
use App\Receipts\Statuses\Draft;
use App\Receipts\Term;
use App\Tag;
use App\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DunController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Dun::with(['invoice.contact', 'settings.level'])
                ->search($request->input('searchtext'))
                ->contact($request->input('contact_id'))
                ->status($request->input('status_type'))
                ->withAllTags($request->input('tags'), 'mahnungen')
                ->orderBy('date', 'DESC')
                ->paginate($request->input('perPage'));
        }

        return view('dun.index')
            ->with('contacts', Contact::all())
            ->with('statuses', Dun::AVAILABLE_STATUSES)
            ->with('labels', Dun::labels())
            ->with('tags', Tag::withType('mahnungen')->get());
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Receipts\dun  $dun
     * @return \Illuminate\Http\Response
     */
    public function show(Dun $dun)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Receipts\dun  $dun
     * @return \Illuminate\Http\Response
     */
    public function edit(Dun $dun)
    {
        $dun->load([
            'items',
            'tags',
        ]);

        $dun->statuses = $dun->statuses()->with('user')->orderBy('date', 'DESC')->orderBy('id', 'DESC')->get();
        $dun->calculateTax();

        return view('dun.edit')
            ->with('dun', $dun)
            ->with('contacts', Contact::all())
            ->with('units', Unit::all())
            ->with('items', Item::all())
            ->with('boilerplates', Boilerplate::all())
            ->with('placeholders', Boilerplate::PLACEHOLDER)
            ->with('terms', Term::where('type', Dun::class)
                ->orderBy('name', 'ASC')
                ->get()
                ->keyBy('id')
            );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Receipts\dun  $dun
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dun $dun)
    {
        $validatedData = $request->validate([
            'address' => 'string',
            'contact_id' => 'required',
            'date' => 'date_format:d.m.Y',
            'number' => 'required|string',
            'subject' => 'string',
            'text_above' => 'string',
            'text_below' => 'string',
        ]);

        $validatedData['date'] = Carbon::createFromFormat('d.m.Y', $validatedData['date']);
        $validatedData['date_due'] = null;

        $oldContactId = $dun->contact_id;

        $dun->update($validatedData);
        $dun->cache();

        return back()
            ->with('status', 'Mahnung gespeichert!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Receipts\dun  $dun
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Dun $dun)
    {
        $dun->statuses()->delete();
        $dun->settings()->delete();
        $dun->delete();

        $invoice = $dun->invoice;
        $invoice->latestDun()->associate($invoice->findLatestDun()->id ?? 0);
        $invoice->save();

        if ($request->wantsJson()) {
            return;
        }

        return redirect('/mahnungen');
    }
}
