<?php

namespace App\Receipts;

use App\Receipts\Expense;
use App\Receipts\Invoice;
use App\Receipts\Item as ReceiptItem;
use App\Receipts\Receipt;
use App\Receipts\Statuses\Completed;
use App\Receipts\Statuses\Draft;
use App\Receipts\Statuses\MorphedTo;
use D15r\ModelPath\Traits\HasModelPath;
use Parental\HasParent;

class Order extends Receipt
{
    use HasParent,
        HasModelPath;

    const AVAILABLE_STATUSES = [
        Draft::class => Draft::NAME,
        Completed::class => Completed::NAME,
    ];

    const LABEL_SINGULAR = 'Auftrag';
    const LABEL_PLURAL = 'Aufträge';
    const ROUTE_NAME = 'receipt.order';
    const SLUG = 'auftraege';
    const URI = '/' . self::SLUG;

    protected $typeName = 'Auftrag';
    protected $uri = self::URI;

    public static function from(Receipt $receipt) : self {

        $attributes = $receipt->getAttributes();
        unset(
            $attributes['type'],
            $attributes['number'],
            $attributes['subject'],
            $attributes['date'],
            $attributes['date_due'],
            $attributes['latest_status_type'],
            $attributes['latest_status_id'],
            $attributes['term_id']
        );

        $order = self::create($attributes);

        foreach ($receipt->items as $item) {
            $attributes = $item->getAttributes();
            $attributes['receipt_id'] = $order->id;
            ksort($attributes);
            $receiptItem = ReceiptItem::make($attributes);
            $receiptItem->receiptable()->associate($item);
            $receiptItem->save();
        }

        foreach ($receipt->todos as $todo) {
            $todo->update([
                'todoable_id' => $order->id,
            ]);
        }

        $order->cache();

        $morphedToStatus = new MorphedTo();
        $morphedToStatus->company_id = $receipt->company_id;
        $morphedToStatus->data = [
            'to_id' => $order->id,
        ];
        $receipt->setStatus($morphedToStatus);
        $receipt->update([
            'receipt_id' => $order->id,
        ]);

        $order->status->data = [
            'from_id' => $receipt->id,
        ];
        $order->status->save();

        return $order;

    }

    protected function getAvailablePaths() : array
    {
        return [
            // 'create_path',
            'edit_path',
            'index_path',
            'path',
        ];
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'order' => $this->id,
        ];
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class, 'receipt_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'receipt_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'receipt_id');
    }

    public function isInvoiceable() : bool
    {
        return $this->latest_status_type == Completed::class;
    }

    public function getNextMainStatusAttribute()
    {
        if ($this->nextMainStatus)
        {
            return $this->nextMainStatus;
        }

        switch ($this->latest_status_type)
        {
            case Draft::class: $this->nextMainStatus = new Completed(); break;
            // case Completed::class: $this->nextMainStatus = new MorphedTo(); $this->nextMainStatus->setActionBelegTyp(); break;
        }

        return $this->nextMainStatus;
    }

    protected function getNameFormat()
    {
        return $this->company->order_name_format;
    }
}
