<?php

namespace App\Http\Controllers\Banks\Accounts;

use App\Banks\Account;
use App\Http\Controllers\Controller;
use App\Tag;
use App\Transaction;
use Illuminate\Http\Request;

class AccountController extends Controller
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
            return Account::with('bank.bank')
                ->orderBy('name', 'ASC')
                ->get();
        }

        return view('account.index');
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
            'name' => 'required|string',
        ]);

        $validatedData['company_id'] = auth()->user()->company_id;

        return Account::create($validatedData)
            ->fresh()
            ->load('banks');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Banks\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Banks\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Banks\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Account $account)
    {
        $attributes = $request->validate([
            'name' => 'required|string',
        ]);

        $account->update($attributes);

        return $account->load([
            'bank.bank',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Banks\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        if ($account->bank_company_id > 0) {
            $account->bank->delete();
        }
        $account->delete();
    }
}
