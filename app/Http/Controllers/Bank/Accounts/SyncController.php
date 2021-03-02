<?php

namespace App\Http\Controllers\Bank\Accounts;

use App\Banks\Account;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SyncController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        $account->load([
            'bank.bank'
        ]);

        $transactions = [];
        try {
            $transactions = $account->import($account->bank->last_import_at ?? now()->startOfYear(), now());

            return [
                'tan' => [
                    'action_path' => null,
                    'html' => '',
                    'show' => false,
                    'tan' => '',
                    'bank_company_id' => $account->bank->id,
                ],
                'transactions' => $transactions,
            ];
        }
        catch(\App\Exceptions\Banks\NeedsTanException $exc) {
            return [
                'tan' => [
                    'action_path' => $exc->path(),
                    'html' => $account->bank->requestTan($exc->action()),
                    'show' => true,
                    'tan' => '',
                    'bank_company_id' => $account->bank->id,
                ],
                'transactions' => $transactions,
            ];
        }
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
