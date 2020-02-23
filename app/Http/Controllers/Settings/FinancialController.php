<?php

namespace App\Http\Controllers\Settings;

use App\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FinancialController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  Level $level
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('settings.financial.edit')
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
            'price' => 'required',
        ]);

        $company = Company::findOrFail(auth()->user()->company_id);
        $company->price = $validatedData['price'];
        if ($company->price < 100)
        {
            throw \Illuminate\Validation\ValidationException::withMessages([
               'price' => [ 'Der Preis muss größer als 1,00 € sein' ],
            ]);
        }
        $company->save();
        $company->prices()->create([
            'price' => $company->price,
        ]);

        return back()
            ->with('status', 'Finanzielles gespeichert!');
    }
}
