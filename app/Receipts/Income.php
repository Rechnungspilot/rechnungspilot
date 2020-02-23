<?php

namespace App\Receipts;

use App\Receipts\Item as ReceiptItem;
use App\Receipts\Receipt;
use App\Receipts\Statuses\Created;
use App\Receipts\Statuses\Draft;
use App\Receipts\Statuses\Expensed;
use App\Receipts\Statuses\MorphedTo;
use App\Receipts\Statuses\Payed;
use App\Receipts\Statuses\Payment;
use App\Receipts\Term;
use Illuminate\Database\Eloquent\Model;
use Parental\HasParent;

class Income extends Receipt
{
    use HasParent;

    const AVAILABLE_STATUSES = [

    ];

    const LABEL_SINGULAR = 'Einnahme';
    const LABEL_PLURAL = 'Einnahmen';
    const SLUG = 'einnahmen';
    const URI = '/' . self::SLUG;

    protected $typeName = 'Einnahme';

    public $dateName = 'Datum';

    public static function from(Receipt $receipt) : self {

        $attributes = $receipt->getAttributes();
        unset(
            $attributes['type'],
            $attributes['number'],
            $attributes['subject'],
            $attributes['date'],
            $attributes['date_due'],
            $attributes['address'],
            $attributes['term_id'],
            $attributes['text_above'],
            $attributes['text_below'],
            $attributes['latest_status_type'],
            $attributes['latest_status_id']
        );

        $attributes['name'] = '';
        $income = self::create($attributes);

        foreach ($receipt->items as $item) {
            $attributes = $item->getAttributes();
            $attributes['receipt_id'] = $income->id;
            ksort($attributes);
            ReceiptItem::create($attributes);
        }

        $income->cache();

        $morphedToStatus = new MorphedTo();
        $morphedToStatus->data = [
            'to_id' => $income->id,
        ];
        $receipt->setStatus($morphedToStatus);

        $draftStatus = new Created();
        $draftStatus->data = [
            'from_id' => $receipt->id,
        ];
        $income->setStatus($draftStatus);

        return $income;
    }

    public function getNextMainStatusAttribute()
    {
        if ($this->nextMainStatus)
        {
            return $this->nextMainStatus;
        }

        switch ($this->latest_status_type) {

        }

        return $this->nextMainStatus;
    }

    public function setName()
    {

    }

    public function previewFile()
    {
        return $this->morphOne('App\Userfile', 'fileable');
    }
}
