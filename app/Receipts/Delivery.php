<?php

namespace App\Receipts;

use App\Receipts\Boilerplate;
use App\Receipts\Receipt;
use App\Receipts\Statuses\Draft;
use App\Receipts\Statuses\MorphedTo;
use App\Receipts\Statuses\Send;
use Illuminate\Database\Eloquent\Model;
use Parental\HasParent;

class Delivery extends Receipt
{
    use HasParent;

    const AVAILABLE_STATUSES = [
        Draft::class => Draft::NAME,
        Send::class => Send::NAME,
        MorphedTo::class => MorphedTo::NAME,
    ];

    const LABEL_SINGULAR = 'Lieferschein';
    const LABEL_PLURAL = 'Lieferscheine';
    const SLUG = 'lieferscheine';
    const URI = '/' . self::SLUG;

    protected $typeName = 'Lieferschein';
    protected $uri = self::URI;

    public $dateName = 'Lieferdatum';

    public static function from(Receipt $receipt) : self {

        $attributes = $receipt->getAttributes();
        unset(
            $attributes['type'],
            $attributes['number'],
            $attributes['subject'],
            $attributes['date'],
            $attributes['date_due'],
            $attributes['term_id'],
            $attributes['text_above'],
            $attributes['text_below'],
            $attributes['latest_status_type'],
            $attributes['latest_status_id']
        );

        $attributes['name'] = '';
        $delivery = self::create($attributes);

        foreach ($receipt->items as $item) {
            $attributes = $item->getAttributes();
            $attributes['receipt_id'] = $delivery->id;
            ksort($attributes);
            ReceiptItem::create($attributes);
        }

        $delivery->cache();

        $morphedToStatus = new MorphedTo();
        $morphedToStatus->data = [
            'to_id' => $delivery->id,
        ];
        $receipt->setStatus($morphedToStatus);

        $draftStatus = new Draft();
        $draftStatus->data = [
            'from_id' => $receipt->id,
        ];
        $delivery->setStatus($draftStatus);

        return $delivery;
    }

    public function isInvoiceable() : bool
    {
        return $this->latest_status_type == Send::class;
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

    public function getMailBoilerplateAttribute()
    {
        return Boilerplate::default(Boilerplate::STANDARD_DELIVERY_MAIL);
    }

    protected function getNameFormat()
    {
        return $this->company->delivery_name_format;
    }

    protected function setTextAbove()
    {
        $this->text_above = Boilerplate::default(Boilerplate::STANDARD_DELIVERY_ABOVE);
    }

    protected function setTextBelow()
    {
        $this->text_below = Boilerplate::default(Boilerplate::STANDARD_DELIVERY_BELOW);
    }

}
