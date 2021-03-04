<?php

namespace App\Http\Controllers;

use App\Company;
use App\Contacts\Contact;
use App\Item;
use App\Receipts\Expense;
use App\Receipts\Invoice;
use App\Receipts\Item as ReceiptItem;
use App\Receipts\Receipt;
use App\Receipts\Term;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PDF;

class DraftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Filesystem $filessytem)
    {
        $attributes = $request->validate([
            'receipt_ids' => 'required|array'
        ]);

        $receipts = Receipt::find($attributes['receipt_ids']);

        $user = auth()->user();
        $base_path = 'app/public/receipts/' . $user->id . '/';
        $path = storage_path($base_path);
        $zip_file = $path . 'belege.zip';

        if (! $filessytem->isDirectory($path)) {
            $filessytem->makeDirectory($path, 0777, true, true);
        }

        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $files = [];
        foreach ($receipts as $receipt) {
            $class_name = get_class($receipt);
            $filename = Str::slug($receipt->name) . '.pdf';
            if ($class_name == Invoice::class) {
                $receipt->pdf()->save($path . $filename);
                $zip->addFile($path . $filename, $filename);
            }
            elseif ($class_name == Expense::class) {
                if (is_null($receipt->previewFile)) {
                    continue;
                }
                copy($receipt->previewFile->url, $path . $filename);
                $zip->addFile($path . $filename, $filename);
            }
            $files[] = $path . $filename;
        }

        $zip->close();

        foreach ($files as $path) {
            unlink($path);
        }

        return [
            'path' => 'storage/receipts/' . $user->id . '/belege.zip',
        ];
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
    public function show(Receipt $receipt = null)
    {
        if (is_null($receipt))
        {
            $contact = Contact::first();
            $term = Term::default(Invoice::class, $contact->invoice_term_id);

            $receipt = Invoice::make([
                'address' => $contact->billing_address,
                'company_id' => auth()->user()->company_id,
                'contact_id' => $contact->id,
                'term_id' => $term->id,
                'date' => now(),
                'date_due' => now()->add($term->days, 'days'),
                'receipt_id' => -1,
            ]);
            $item = Item::with('unit')->first();
            $receiptItem = ReceiptItem::make([
                'item_id' => $item->id,
                'unit_id' => $item->unit_id,
                'name' => $item->name,
                'description' => $item->description,
                'quantity' => 1,
                'discount' => 0,
                'tax' => $item->tax,
                'unit_price' => $item->unit_price,
                'unit' => $item->unit,
            ]);
            $receipt->items = collect([$receiptItem]);
        }

        return $receipt->pdf()->stream();
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

    public function pdf(Receipt $receipt)
    {
        return $receipt->pdf()->download($receipt->filename);
    }
}
