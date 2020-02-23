<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;

class CompanyFinancialController extends Controller
{
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
            'price' => 'required|min:1',
        ]);

        $company->update($validatedData);

        return redirect('/firma/edit#finanzielles')
            ->with('status', 'Änderungen gespeichert!');
    }
}
