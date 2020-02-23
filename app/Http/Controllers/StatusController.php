<?php

namespace App\Http\Controllers;

use App\Item;
use App\Jobs\CacheContact;
use App\Jobs\CacheItem;
use App\Mail\ReceiptSend;
use App\Receipts\Expense;
use App\Receipts\Item as ReceiptItem;
use App\Receipts\Receipt;
use App\Receipts\Statuses\Declined;
use App\Receipts\Statuses\Payment;
use App\Receipts\Statuses\Send;
use App\Receipts\Statuses\Status;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

class StatusController extends Controller
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
    public function create(Request $request, Receipt $receipt)
    {
        $validatedData = $request->validate([
            'type' => 'string',
        ]);

        $status = new $validatedData['type']();
        $status->receipt = $receipt;

        $body = View::make('receipt.status.create', [
            'status' => $status,
            'receipt' => $receipt
        ])->render();

        return [
            'title' => $receipt->typeName . ' ' . $status->action,
            'body' => $body,
            'action' => $status->action,
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Receipt $receipt)
    {
        $validatedData = $request->validate($this->rules($request->input('type')));

        $status = new $validatedData['type']();
        $status->associate($receipt, $validatedData);

        if ($request->wantsJson()) {
            return $status;
        }

        return back()
            ->with('status', 'Status gespeichert');
    }

    protected function rules(string $type)
    {
        $rules = [
            'type' => 'string',
            'date' => 'date_format:d.m.Y',
        ];

        switch ($type) {
            case Declined::class:
                $rules['text'] = 'nullable|string';
                break;
            case Send::class:
                $rules['send_mail'] = 'nullable';
                $rules['email'] = 'required_with:send_mail';
                $rules['text'] = 'nullable|string';
                $rules['userfiles'] = 'sometimes|array';
                $rules['userfiles.*'] = 'required_with:userfiles';
                break;
            case Payment::class:
                $rules['account_id'] = 'required';
                $rules['amount'] = 'required';
                $rules['completed'] = 'nullable';
                break;
        }

        return $rules;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Receipts\Statuses\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function show(Status $status)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Receipts\Statuses\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function edit(Status $status)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Receipts\Statuses\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Status $status)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Receipts\Statuses\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function destroy(Status $status)
    {
        $receipt = $status->receipt;
        $isLatestStatus = $receipt->status->is($status);
        if ($status->type == Payment::class)
        {
            $status->transaction->delete();
        }
        $status->delete();
        if (! $isLatestStatus)
        {
            return back()
                ->with('status', 'Status gelöscht');
        }

        $receipt->setLatestStatus();

        $receipt->cache();

        return back()
            ->with('status', 'Status gelöscht. Aktueller Status ' . $receipt->latest_status_type::NAME);

    }
}
