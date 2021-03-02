<?php

namespace App\Http\Controllers;

use App\Banks\Bank;
use App\Banks\Company;
use Fhp\Dialog\Exception\FailedRequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->wantsJson())
        {
            return Bank::search($request->input('searchtext'))
                ->get();
        }
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
            'bank_id' => 'required',
            'username' => 'required',
            'pin' => 'required',
        ]);

        $validatedData['company_id'] = auth()->user()->company_id;

        $bankCompanies = Company::with('bank')->where('bank_id', $validatedData['bank_id'])->get();
        $bankCompany = null;
        foreach ($bankCompanies as $key => $bank) {
            if ($bank->username == $validatedData['username']) {
                $bankCompany = $bank;
                break;
            }
        }
        if (is_null($bankCompany)) {
            $bankCompany = Company::make($validatedData);
            $bankCompany->bank = Bank::find($validatedData['bank_id']);
        }
        $accounts = [];

        unset($bankCompany->bank);
        $bankCompany->save();

        try
        {
            $sepaAccounts = $bankCompany->getSEPAAccounts();
            foreach ($sepaAccounts as $key => $sepaAccount) {
                $accounts[] = [
                    'iban' => $sepaAccount->getIban(),
                    'name' => $sepaAccount->getIban(),
                    'bic' => $sepaAccount->getBic(),
                    'accountNumber' => $sepaAccount->getAccountNumber(),
                    'blz' => $sepaAccount->getBlz(),
                ];
            }

            return [
                'tan' => [
                    'action_path' => null,
                    'html' => '',
                    'show' => false,
                    'tan' => '',
                ],
                'bank_company_id' => $bankCompany->id,
                'accounts' => $accounts,
            ];
        }
        catch(FailedRequestException $exc)
        {
            $error = \Illuminate\Validation\ValidationException::withMessages([
               'pin' => [ $exc->getMessage() ],
            ]);
            throw $error;
        }
        catch(\App\Exceptions\Banks\NeedsTanException $exc)
        {
            return [
                'tan' => [
                    'action_path' => $exc->path(),
                    'html' => $bankCompany->requestTan($exc->action()),
                    'show' => true,
                    'tan' => '',
                ],
                'bank_company_id' => $bankCompany->id,
                'accounts' => $accounts,
            ];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Banks\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function show(Bank $bank)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Banks\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function edit(Bank $bank)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Banks\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bank $bank)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Banks\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bank $bank)
    {
        //
    }
}
