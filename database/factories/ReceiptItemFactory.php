<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Item;
use App\Receipts\Invoice;
use App\Receipts\Item as ReceiptItem;
use App\Unit;
use Faker\Generator as Faker;

$factory->define(ReceiptItem::class, function (Faker $faker) {
    $invoice = factory(Invoice::class)->create();
    $unit = factory(Unit::class)->create([
        'company_id' => $invoice->company_id,
    ]);
    $item = factory(Item::class)->create([
        'company_id' => $invoice->company_id,
        'unit_id' => $unit->id,
    ]);

    return [
        'company_id' => $invoice->company_id,
        'discount' => 0,
        'item_id' => $item->id,
        'name' => $item->name,
        'quantity' => 1,
        'receipt_id' => $invoice->id,
        'tax' => 0.19,
        'unit_id' => $unit->id,
        'unit_price' => number_format(0, 6),
    ];
});
