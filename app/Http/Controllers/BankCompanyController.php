<?php

namespace App\Http\Controllers;

use App\Banks\Account;
use App\Banks\Bank;
use App\Banks\Company;
use Carbon\Carbon;
use Fhp\Dialog\Exception\FailedRequestException;
use Illuminate\Http\Request;

class BankCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $validatedData = $request->validate([
            'bank_company_id' => 'required',
            'accounts' => 'required|array',
            'date' => 'date_format:d.m.Y',
        ]);

        $importFrom = Carbon::createFromFormat('d.m.Y', $validatedData['date']);

        $companyId = auth()->user()->company_id;
        $accounts = [];
        $validatedData['accounts'] = array_filter($validatedData['accounts']);
        foreach ($validatedData['accounts'] as $key => $account) {
            $accounts[$key] = Account::create([
                'company_id' => $companyId,
                'bank_company_id' => $validatedData['bank_company_id'],
                'iban' => $account['iban'],
                'name' => $account['iban'],
                'amount' => 0,
            ]);
            $accounts[$key]->load('bank.bank');
            $accounts[$key]->import($importFrom, now());
        }

        return $accounts;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
