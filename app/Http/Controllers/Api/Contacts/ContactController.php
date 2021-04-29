<?php

namespace App\Http\Controllers\Api\Contacts;

use App\Company;
use App\Contacts\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Company $company)
    {
        return $company->contacts()
            ->email($request->input('email'))
            ->paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Company $company)
    {
        $attributes = $request->validate([
            'firstname' => 'nullable|string',
            'lastname' => 'nullable|string',
            'company' => 'nullable|string',
            'email' => 'required|email',
            'city' => 'required|string|max:255',
            'postcode' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $model = $company->contacts()->create($attributes);

        return $model;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Company $company, Contact $contact)
    {
        return $contact;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
