<?php

namespace App\Http\Controllers\Receipts\Exports\Datev;

use App\Exports\Receipts\Datev;
use App\Http\Controllers\Controller;
use App\Receipts\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SingleController extends Controller
{
    public function index(Request $request)
    {
        $attributes = $request->validate([
            'receipt_ids' => 'required|array'
        ]);

        $receipts = Receipt::with([
            'contact',
            'items.item',
        ])->find($attributes['receipt_ids']);

        $path = Datev::invoices(auth()->user()->company, $receipts);

        return [
            'path' => Storage::disk('public')->url($path),
        ];
    }
}
