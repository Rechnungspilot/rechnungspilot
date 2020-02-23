<?php

namespace App\Http\Controllers\Settings;

use App\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountingController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  Level $level
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('settings.accounting.edit')
            ->with('company', Company::findOrFail(auth()->user()->company_id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  Level $level
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'debitor_account_number_mode' => 'required|numeric',
            'creditor_account_number_mode' => 'required|numeric',
            'default_debitor_account_number' => 'required|numeric',
            'default_creditor_account_number' => 'required|numeric',
            'revenue_account_number_19' => 'required|numeric',
            'revenue_account_number_7' => 'required|numeric',
            'revenue_account_number_0_inland' => 'required|numeric',
            'default_revenue_account_number' => 'required|numeric',
            'default_expense_account_number' => 'required|numeric',
        ]);

        $company = Company::findOrFail(auth()->user()->company_id);
        $company->update($validatedData);

        return back()
            ->with('status', 'Buchhaltung gespeichert!');
    }
}
