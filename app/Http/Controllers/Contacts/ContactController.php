<?php

namespace App\Http\Controllers\Contacts;

use App\Contacts\Contact;
use App\Http\Controllers\Controller;
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
                ->withAllTags($request->input('tags'), Contact::class)
                ->orderBy('company', 'DESC')
                ->paginate($request->input('perPage'));
            foreach ($contacts as $key => $contact) {
                $contact->append('tagsString');
            }

            return $contacts;
        }

        return view('contact.index')
            ->with('tags', Tag::withType(Contact::class)->get());
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

        if ($request->wantsJson()) {
            return response()->json($contact->fresh(), 201);
        }

        return redirect($contact->edit_path);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        $contact->load([
            'abos',
            'customfields',
            'people',
            'receipts',
            'tags',
        ]);

        return view('contact.show')
            ->with('contact', $contact)
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
    public function edit(Contact $contact)
    {
        $contact->load([
            'customfields',
            'tags',
        ]);

        return view('contact.edit')
            ->with('contact', $contact)
            ->with('customfields', CustomField::for($contact))
            ->with('tags', Tag::withType(Contact::class)->get())
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
    public function update(Request $request, contact $contact)
    {
        $validatedData = $request->validate([
            'address' => 'nullable|string|max:255',
            'billing_address' => 'nullable|string',
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

        $contact->customfields->validate($request)
            ->update();

        $contact->update($validatedData);

        return redirect($contact->path)
            ->with('status', 'Änderungen gespeichert!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Contact $contact)
    {
        $isDeletable = $contact->isDeletable();
        if ($isDeletable) {
            $contact->delete();
        }

        if ($request->wantsJson()) {
            return;
        }

        return redirect($contact->index_path)
            ->with('status', 'Kontakt ' . ($isDeletable ? '' : ' nicht ') . ' gelöscht!');
    }
}
