<?php

namespace App\Http\Controllers\Contacts\People;

use App\Contacts\Contact;
use App\Contacts\Person;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DefaultController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Contact $contact, Person $person, string $type)
    {
        Person::setDefault($person, $type);

        if ($request->wantsJson()) {
            return;
        }

        return back()
            ->with('status', 'Standard gespeichert!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Contact $contact, Person $person, string $type)
    {
        $person->update([
            'default_' . $type => 0,
        ]);

        if ($request->wantsJson()) {
            return;
        }

        return back()
            ->with('status', 'Standard gespeichert!');
    }
}
