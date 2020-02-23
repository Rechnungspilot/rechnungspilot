<?php

namespace App\Http\Controllers;

use App\Contacts\Contact;
use App\Receipt\Letter;
use App\Receipts\Boilerplate;
use App\Receipts\Statuses\Draft;
use App\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LetterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Letter::with(['contact', 'status'])
                ->search($request->input('searchtext'))
                ->contact($request->input('contact_id'))
                ->status($request->input('status_type'))
                ->withAllTags($request->input('tags'), 'briefe')
                ->orderBy('date', 'DESC')
                ->paginate($request->input('perPage'));
        }

        return view('letter.index')
            ->with('contacts', Contact::all())
            ->with('statuses', Letter::AVAILABLE_STATUSES)
            ->with('labels', Letter::labels())
            ->with('tags', Tag::withType('briefe')->get());
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

        $receipt = Letter::create([
            'address' => $contact->billingAddress,
            'company_id' => auth()->user()->company_id,
            'contact_id' => $contact->id,
        ]);

        if ($request->wantsJson()) {
            $receipt->cache();
            return $receipt;
        }

        return redirect($receipt->path)
            ->with('status', 'Brief erstellt!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Receipts\Delivery  $letter
     * @return \Illuminate\Http\Response
     */
    public function show(Letter $letter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Receipts\Delivery  $letter
     * @return \Illuminate\Http\Response
     */
    public function edit(Letter $letter)
    {
        $letter->load([
            'tags',
        ]);

        $letter->statuses = $letter->statuses()->with('user')->orderBy('date', 'DESC')->orderBy('id', 'DESC')->get();

        return view('letter.edit')
            ->with('letter', $letter)
            ->with('contacts', Contact::all())
            ->with('boilerplates', Boilerplate::all())
            ->with('placeholders', Boilerplate::PLACEHOLDER)
            ->with('tags', Tag::withType('briefe')->get());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Receipts\Delivery  $letter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Letter $letter)
    {
        $validatedData = $request->validate([
            'address' => 'string',
            'contact_id' => 'required',
            'date' => 'date_format:d.m.Y',
            'subject' => 'nullable|string',
            'text' => 'nullable|string',
            'text_above' => 'nullable|string',
            'text_below' => 'nullable|string',
        ]);

        $validatedData['date'] = Carbon::createFromFormat('d.m.Y', $validatedData['date']);

        $letter->update($validatedData);
        $letter->cache();

        return back()
            ->with('status', 'Brief gespeichert!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Receipts\Delivery  $letter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Letter $letter)
    {
        $letter->statuses()->delete();
        $letter->delete();

        if ($request->wantsJson()) {
            return;
        }

        return redirect('/briefe');
    }
}
