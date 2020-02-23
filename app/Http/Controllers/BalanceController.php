<?php

namespace App\Http\Controllers;

use App\Banks\Account;
use App\Company;
use App\Transaction;
use Carbon\Carbon;
use Fhp\FinTs;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $account = Account::fromIban('DE91701204008492435014');

        if ($request->wantsJson())
        {
            return Transaction::account($account->id)
                ->company($request->input('company_id'))
                ->orderBy('created_at', 'DESC')
                ->paginate($request->input('perPage'));
        }

        $transactions = Transaction::account($account->id)
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('balance.create')
            ->with('companies', Company::all())
            ->with('transactions', $transactions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $account = Account::fromIban('DE91701204008492435014');
        $transactions = Transaction::where('account_id', $account->id)
            ->where('transactionable_id', 0)
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('balance.create')
            ->with('companies', Company::all())
            ->with('transactions', $transactions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, Transaction $transaction)
    {
        $validatedData = $request->validate([
            'transactionable_id' => 'required|numeric',
        ]);

        $validatedData['transactionable_type'] = Company::class;

        $transaction->update($validatedData);

        Company::findOrFail($validatedData['transactionable_id'])->increment('balance', $transaction->amount);

        if ($request->wantsJson())
        {
            return $transaction;
        }
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
