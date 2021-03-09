<?php

namespace App\Http\Controllers\Contacts;

use App\Contacts\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Contact $contact)
    {
        return $contact->billing_address;
    }
}
