<?php

namespace App\Http\Controllers\Times;

use App\Time;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InvoiceController extends Controller
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
    public function store(Request $request, Time $time = null)
    {
        if (is_null($time))
        {
            $validatedData = $request->validate([
                'time_ids' => 'required|array',
            ]);

            $times = Time::whereIn('id', $validatedData['time_ids'])->get();

            $invoice = null;
            foreach ($times as $key => $time)
            {
                if (is_null($time->end_at))
                {
                    continue;
                }
                $invoice = $time->toInvoice($invoice);
            }
            if (is_null($invoice))
            {
                abort(500, 'Something went wrong');
            }
        }
        else
        {
            $invoice = $time->toInvoice();
        }

        if ($request->wantsJson())
        {
            $invoice->cache();
            return $invoice;
        }

        return redirect($invoice->path);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Time  $time
     * @return \Illuminate\Http\Response
     */
    public function show(Time $time)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Time  $time
     * @return \Illuminate\Http\Response
     */
    public function edit(Time $time)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Time  $time
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Time $time)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Time  $time
     * @return \Illuminate\Http\Response
     */
    public function destroy(Time $time)
    {
        //
    }
}
