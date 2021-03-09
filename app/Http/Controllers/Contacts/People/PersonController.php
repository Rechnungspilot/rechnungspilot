<?php

namespace App\Http\Controllers\Contacts\People;

use App\Contacts\Contact;
use App\Contacts\Person;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Contact $contact)
    {
        return $contact->people()
            ->search($request->input('searchtext'))
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
    public function store(Request $request, Contact $contact)
    {
        return Person::create([
            'company_id' => auth()->user()->company_id,
            'contact_id' => $contact->id,
            'default_invoice' => 0,
            'default_quote' => 0,
            'firstname' => 'Ansprechpartner',
            'lastname' => 'Neuer',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contacts\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact, Person $person)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contacts\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact, Person $person)
    {
        return view('contact.person.edit')
            ->with('contact', $contact)
            ->with('person', $person);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contacts\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact, Person $person)
    {
        $validatedData = $request->validate([
            'email' => 'nullable|email',
            'firstname' => 'nullable|string',
            'function' => 'nullable|string',
            'lastname' => 'nullable|string',
            'mobilenumber' => 'nullable|string',
            'phonenumber' => 'nullable|string',
            'title' => 'nullable|string',
        ]);
        $person->update($validatedData);

        return redirect($contact->path)
            ->with('status', 'Ansprechpartner gespeichert!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contacts\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Contact $contact, Person $person)
    {
        $person->delete();
    }
}
