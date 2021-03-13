<?php

namespace App\Http\Controllers\Receipts\Abos;

use App\Http\Controllers\Controller;
use App\Receipts\Abos\Abo;
use Illuminate\Http\Request;

class ActiveController extends Controller
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
    public function store(Request $request, string $type, Abo $subscription)
    {
        $subscription->settings()->update([
            'active' => true,
        ]);

        if ($subscription->settings->next_at <= today()) {
            $receipt = $subscription->toReceipt();
        }

        return back()
            ->with('status', 'Abo aktiv!');
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
    public function destroy(Request $request, string $type, Abo $subscription)
    {
        $subscription->settings()->update([
            'active' => false,
        ]);

        return back()
            ->with('status', 'Abo inaktiv!');
    }
}
