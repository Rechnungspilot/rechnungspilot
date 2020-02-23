<?php

namespace App\Http\Controllers;

use App\Receipts\Delivery;
use App\Receipts\Receipt;
use Illuminate\Http\Request;

class DeliveryFromController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Receipt $receipt)
    {
        $delivery = Delivery::from($receipt);

        return redirect($delivery->path)
            ->with('status', $delivery->typeName . ' erstellt');
    }
}
