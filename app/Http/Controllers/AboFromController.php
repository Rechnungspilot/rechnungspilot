<?php

namespace App\Http\Controllers;

use App\Receipts\Abos\Abo;
use App\Receipts\Receipt;
use Illuminate\Http\Request;

class AboFromController extends Controller
{
    public function store(Request $request, Receipt $receipt)
    {
        $abo = Abo::from($receipt, (bool) $request->input('credit'));

        return redirect($abo->path)
            ->with('status', $abo->typeName . ' erstellt');
    }
}
