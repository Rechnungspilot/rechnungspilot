<?php

namespace App\Http\Controllers\Api\Items;

use App\Company;
use App\Http\Controllers\Controller;
use App\Item;
use App\Receipts\Invoice;
use Illuminate\Http\Request;

class LineController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Company $company, Item $item)
    {
        return $item->load([
            'receiptItems' => function ($query) use ($request) {
                return $query->where('receipts.type', Invoice::class);
            },
        ]);
    }
}
