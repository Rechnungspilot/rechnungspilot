<?php

namespace App\Http\Controllers;

use App\Banks\Account;
use App\Receipts\Receipt;
use App\Receipts\Statuses\Payment;
use App\Tag;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
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
            $transactions = Transaction::with(['transactionable', 'tags', 'payments.receipt.contact'])
                ->account($request->input('account_id') ?? 0)
                ->company($request->input('company_id') ?? 0)
                ->withAllTags($request->input('tags'), 'buchungen')
                ->orderBy('date', 'DESC')
                ->paginate($request->input('perPage'));
            foreach ($transactions as $key => $transaction) {
                $transaction->append('tagsString');
            }

            return $transactions;
        }

        return view('transaction.index')
            ->with('accounts', Account::all())
            ->with('tags', Tag::withType('buchungen')->get());
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
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        $validatedData = $request->validate([
            'amount' => 'required',
            'date' => 'date_format:d.m.Y',
            'receipt_ids' => 'required|array',
        ]);

        $validatedData['amount'] = str_replace(',', '.', $validatedData['amount']) * 100;
        $validatedData['date'] = Carbon::createFromFormat('d.m.Y', $validatedData['date']);
        $receipts = $validatedData['receipt_ids'];
        unset($validatedData['receipt_ids']);

        $transaction->update($validatedData);

        $transaction->load('payments.receipt');
        $paymentIds = [];
        foreach ($receipts as $receiptId => $attributes) {
            if (is_null($attributes))
            {
                unset($validatedData['receipt_ids'][$receiptId]);
                continue;
            }

            $attributes['date'] = $validatedData['date'];
            $attributes['account_id'] = $transaction->account_id;
            if ($attributes['payment_id'] == 0)
            {
                unset($attributes['payment_id']);
                $attributes['transaction_id'] = $transaction->id;
                $payment = (new Payment())->associate(Receipt::find($receiptId), $attributes);
            }
            else
            {
                $payment = $transaction->payments->where('id', $attributes['payment_id'])->first();
                $payment->update([
                    'date' => $validatedData['date'],
                    'data' => [
                        'amount' => str_replace(',', '.', $attributes['amount']) * 100,
                        'completed' => $attributes['completed'],
                        'transaction_id' => $transaction->id,
                    ],
                ]);
                if ($attributes['completed'] == true && is_null($payment->receipt->payedStatus))
                {
                    $payment->receipt->paid($validatedData['date']);
                }
                elseif ($attributes['completed'] == true && $payment->receipt->payedStatus)
                {
                    $payment->receipt->payedStatus->update([
                        'date' => $validatedData['date'],
                        'data' => [
                            'outstanding' => $payment->receipt->calculateOutstanding(),
                        ],
                    ]);
                }
                elseif ($attributes['completed'] == false && $payment->receipt->payedStatus)
                {
                    $payment->receipt->delStatus($payment->receipt->payedStatus);
                }
                $payment->receipt->cache();
            }
            $paymentIds[] = $payment->id;
        }

        foreach ($transaction->payments as $key => $payment) {
            if (in_array($payment->id, $paymentIds))
            {
                continue;
            }
            $payment->receipt->refresh();
            $payment->receipt->delStatus($payment);
        }

        return $transaction->fresh(['payments.receipt.contact'])->append('tagsString');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
