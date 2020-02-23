<?php

namespace App\Http\Controllers\Settings;

use App\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NumbersController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  Level $level
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('settings.numbers.edit')
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
            'abo_name_format' => 'string',
            'delivery_name_format' => 'string',
            'invoice_name_format' => 'string',
            'order_name_format' => 'string',
            'quote_name_format' => 'string',
        ]);

        $company = Company::findOrFail(auth()->user()->company_id);
        $company->update($validatedData);

        return back()
            ->with('status', 'Nummernkreise gespeichert!');
    }
}
