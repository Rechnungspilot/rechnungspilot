<?php

namespace App\Http\Controllers\Receipts;

use App\Receipts\Receipt;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use horstoeko\zugferd\ZugferdXsdValidator;

class XRechnungController extends Controller
{
    public function show(Request $request, Receipt $receipt)
    {
        $ZugferdXsdValidator = new ZugferdXsdValidator($receipt->xrechnung());
        $ZugferdXsdValidator->validate();

        if ($ZugferdXsdValidator->hasValidationErrors()) {
            $errors = $ZugferdXsdValidator->validationErrors();
            return response()->json($errors, 400);
        }

        return response($receipt->xrechnung()->getContent(), Response::HTTP_OK, [
            'Content-Type' => 'application/xml'
        ]);

    }
}
