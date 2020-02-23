<?php

namespace App\Http\Controllers;

use App\Contacts\Contact;
use App\Models\CustomFields\CustomField;
use App\Receipts\Expense;
use App\Receipts\Invoice;
use App\Receipts\Term;
use App\Tag;
use App\User;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $contacts = Contact::with([
                'tags'
                ])->search($request->input('searchtext'))
                ->withAllTags($request->input('tags'), 'kontakte')
                ->orderBy('company', 'DESC')
                ->paginate($request->input('perPage'));
            foreach ($contacts as $key => $contact) {
                $contact->append('tagsString');
            }

            return $contacts;
        }

        return view('contact.index')
            ->with('tags', Tag::withType('kontakte')->get());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function raw(Request $request)
    {
        return Contact::search($request->input('searchtext'))
            ->orderBy('number', 'ASC')
            ->orderBy('company', 'ASC')
            ->orderBy('lastname', 'ASC')
            ->orderBy('firstname', 'ASC')
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
        $contact = Contact::create([
            'company_id' => auth()->user()->company_id,
            'firstname' => 'Neuer',
            'lastname' => 'Kunde',
            'number' => Contact::nextNumber(),
        ]);

        if ($request->wantsJson())
        {
            return response()->json($contact->fresh(), 201);
        }

        return redirect('/kontakte/' . $contact->getRouteKey());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $kontakte)
    {
        $kontakte->load([
            'abos',
            'customfields',
            'people',
            'receipts',
            'tags',
        ]);

        return view('contact.show')
            ->with('contact', $kontakte)
            ->with('net', 0)
            ->with('tax_value', 0)
            ->with('gross', 0)
            ->with('users', User::all());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $kontakte)
    {
        $kontakte->load([
            'customfields',
            'tags',
        ]);

        return view('contact.edit')
            ->with('contact', $kontakte)
            ->with('customfields', CustomField::for($kontakte))
            ->with('tags', Tag::withType('kontakte')->get())
            ->with('terms', Term::where('type', Invoice::class)
                ->orderBy('name', 'ASC')
                ->get()
                ->keyBy('id')
            )->with('termsExpense', Term::where('type', Expense::class)
                ->orderBy('name', 'ASC')
                ->get()
                ->keyBy('id')
            );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, contact $kontakte)
    {
        $validatedData = $request->validate([
            'address' => 'nullable|string|max:255',
            'bankname' => 'nullable|string|max:255',
            'bic' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'email' => 'nullable|string||max:255',
            'email_receipt' => 'required|numeric',
            'euvatnumber' => 'nullable|string|max:255',
            'expense_term_id' => 'integer|min:0',
            'faxnumber' => 'nullable|string|max:255',
            'firstname' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            'invoice_term_id' => 'required|numeric|min:0',
            'lastname' => 'nullable|string|max:255',
            'mobilenumber' => 'nullable|string|max:255',
            'number' => 'nullable|string|max:255',
            'phonenumber' => 'nullable|string|max:255',
            'postcode' => 'nullable|string|max:255',
            'vatnumber' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'debitor_account_number' => 'nullable|numeric',
            'creditor_account_number' => 'nullable|numeric',
            'company_number' => 'nullable|string|max:255',
        ]);

        $kontakte->customfields->validate($request)
            ->update();

        $kontakte->update($validatedData);

        return redirect($kontakte->path)
            ->with('status', 'Änderungen gespeichert!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Contact $kontakte)
    {
        $isDeletable = $kontakte->isDeletable();
        if ($isDeletable)
        {
            $kontakte->delete();
        }

        if ($request->wantsJson())
        {
            return;
        }

        return redirect('/kontakte')
            ->with('status', 'Kontakt ' . ($isDeletable ? '' : ' nicht ') . ' gelöscht!');
    }
}
