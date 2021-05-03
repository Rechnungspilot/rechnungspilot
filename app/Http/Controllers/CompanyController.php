<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $companies = $user->companies()->orderBy('name', 'ASC')->get();

        return view('company.index')
            ->with('companies', $companies)
            ->with('current_company', Company::find(session('user.company.id')));
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
        $user = auth()->user();

        $company = Company::create([
            'email' => $user->email,
            'name' => 'Meine Firma',
            'abo_name_format' => '#DATUM-JJ#-#NUMMER-4#',
            'invoice_name_format' => '#DATUM-JJ#-#NUMMER-4#',
            'order_name_format' => '#DATUM-JJ#-#NUMMER-4#',
            'quote_name_format' => '#DATUM-JJ#-#NUMMER-4#',
            'delivery_name_format' => '#DATUM-JJ#-#NUMMER-4#',
            'expense_name_format' => '#DATUM-JJ#-#NUMMER-4#',
            'price' => 10,
            // 'charging_start_at' => $charge_at,
            // 'charging_next_at' => $charge_at,
        ]);

        $company->setup();

        $user->companies()->attach($company->id);

        if ($request->wantsJson()) {
            return $company;
        }

        return back()
            ->with('status', 'Firma angelegt');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return view('company.show')
            ->with('company', $company);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $company = Company::findOrFail(auth()->user()->company_id);

        return view('company.edit')->with('company', $company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $validatedData = $request->validate([
            'accountholdername' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'bankname' => 'nullable|string|max:255',
            'bic' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'districtcourt' => 'nullable|string|max:255',
            'email' => 'required|email|unique:companies,email,' . $company->id,
            'euvatnumber' => 'nullable|string|max:255',
            'faxnumber' => 'nullable|string|max:255',
            'firstname' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'phonenumber' => 'nullable|string|max:255',
            'postcode' => 'nullable|string|max:255',
            'traderegister' => 'nullable|string|max:255',
            'vatnumber' => 'nullable|string|max:255',
            'web' => 'nullable|string|max:255',
        ]);

        $validatedData['sales_tax'] = $request->filled('sales_tax');

        $company->update($validatedData);

        $request->session()->put('user.company', $company->only([
            'id',
            'name',
        ]));

        return back()->with('status', 'Änderungen gespeichert!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        //
    }
}
