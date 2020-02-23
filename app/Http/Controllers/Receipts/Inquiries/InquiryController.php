<?php

namespace App\Http\Controllers\Receipts\Inquiries;

use App\Contacts\Contact;
use App\Http\Controllers\Controller;
use App\Item;
use App\Receipts\Boilerplate;
use App\Receipts\Inquiries\Inquiry;
use App\Tag;
use App\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            return Inquiry::with(['contact', 'status'])
                ->search($request->input('searchtext'))
                ->contact($request->input('contact_id'))
                ->status($request->input('status_type'))
                ->withAllTags($request->input('tags'), 'anfragen')
                ->orderBy('date', 'DESC')
                ->paginate(15);
        }

        return view('inquiry.index')
            ->with('contacts', Contact::all())
            ->with('statuses', Inquiry::AVAILABLE_STATUSES)
            ->with('labels', Inquiry::labels())
            ->with('tags', Tag::withType('anfragen')->get());
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
        $now = now();

        $inquiry = Inquiry::create([
            'address' => null,
            'company_id' => auth()->user()->company_id,
            'contact_id' => $contact->id,
            'date' => $now,
            'name' => 'Anfrage vom ' . $now->format('d.m.Y'),
            'term_id' => null,
        ]);

        if ($request->wantsJson()) {
            $inquiry->cache();
            return $inquiry;
        }

        return redirect($inquiry->path);
    }

    /**
     * Display the specified resource.
     *
     * @param  Inquiry $inquiry
     * @return \Illuminate\Http\Response
     */
    public function show(Inquiry $inquiry)
    {
        $inquiry->load([
            'tags',
            'status',
        ]);

        $inquiry->statuses = $inquiry->statuses()->with('user')->orderBy('date', 'DESC')->orderBy('id', 'DESC')->get();

        return view('inquiry.show')
            ->with('model', $inquiry);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Inquiry $inquiry
     * @return \Illuminate\Http\Response
     */
    public function edit(Inquiry $inquiry)
    {
        $inquiry->load([
            'tags',
        ]);

        $inquiry->statuses = $inquiry->statuses()->with('user')->orderBy('date', 'DESC')->orderBy('id', 'DESC')->get();

        return view('inquiry.edit')
            ->with('model', $inquiry)
            ->with('contacts', Contact::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Inquiry $inquiry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inquiry $inquiry)
    {
        $validatedData = $request->validate([
            'contact_id' => 'required',
            'date' => 'date_format:d.m.Y',
            'name' => 'nullable|string',
            'text' => 'nullable|string',
        ]);

        $validatedData['date'] = Carbon::createFromFormat('d.m.Y', $validatedData['date'])->startOfDay();

        $inquiry->update($validatedData);
        $inquiry->cache();

        return back()->with('status', 'Anfrage gespeichert!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Inquiry $inquiry
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Inquiry $inquiry)
    {
        if ($isDeletable = $inquiry->isDeletable()) {
            $inquiry->delete();
        }

        if ($request->wantsJson())
        {
            return [
                'deleted' => $isDeletable,
            ];
        }

        return redirect(route('receipt.inquiry.index'));
    }
}
