<?php

namespace App\Http\Controllers;

use App\Contacts\Contact;
use App\Receipts\Receipt;
use Illuminate\Http\Request;

class ContactRevenueController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        return Receipt::revenueByMonth(auth()->user()->company_id, $contact->id);
    }
}
