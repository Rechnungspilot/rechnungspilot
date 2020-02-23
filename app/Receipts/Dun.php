<?php

namespace App\Receipts;

use App\Receipts\Duns\Level;
use App\Receipts\Invoice;
use App\Receipts\Item as ReceiptItem;
use App\Receipts\Receipt;
use App\Receipts\Statuses\Draft;
use Illuminate\Database\Eloquent\Model;
use Parental\HasParent;

class Dun extends Receipt
{
    use HasParent;

    const AVAILABLE_STATUSES = [

    ];

    const LABEL_SINGULAR = 'Mahnung';
    const LABEL_PLURAL = 'Mahnungen';
    const SLUG = 'mahnungen';
    const URI = '/' . self::SLUG;

    protected $typeName = 'Mahnung';
    protected $uri = self::URI;

    public $dateName = 'Mahnungsdatum';

    public static function from(Invoice $receipt, Level $level) : self
    {
        $attributes = $receipt->getAttributes();
        unset(
            $attributes['type'],
            $attributes['number'],
            $attributes['subject'],
            $attributes['date'],
            $attributes['date_due'],
            $attributes['latest_status_type'],
            $attributes['latest_status_id']
        );
        $attributes['receipt_id'] = $receipt->id;
        $attributes['subject'] = $level->name . ' zu ' . $receipt->typeName . ' ' . $receipt->name;
        $dun = self::create($attributes);

        ReceiptItem::create([
            'receipt_id' => $dun->id,
            'item_id' => 0,
            'unit_id' => 0,
            'name' => $receipt->typeName . ' ' . $receipt->name,
            'description' => '',
            'quantity' => 1,
            'discount' => 0,
            'tax' => 0,
            'unit_price' => $receipt->gross / 100,
        ]);

        if ($level->item_id)
        {
            ReceiptItem::create([
                'receipt_id' => $dun->id,
                'item_id' => $level->item->id,
                'unit_id' => $level->item->unit_id,
                'name' => $level->item->name,
                'description' => $level->item->description,
                'quantity' => 1,
                'discount' => 0,
                'tax' => $level->item->tax,
                'unit_price' => $level->amount / 100,
            ]);
        }
        $dun->cache();

        $draftStatus = new Draft();
        $draftStatus->company_id = $dun->company_id;
        $draftStatus->data = [
            'from_id' => $receipt->id,
        ];
        $dun->setStatus($draftStatus);

        $dun->settings()->create([
            'action' => $level->action,
            'attach_invoice' => $level->attach_invoice,
            'company_id' => $dun->company_id,
            'dun_id' => $dun->id,
            'email' => '',
            'level_id' => $level->id,
            'waiting_days' => $level->waiting_days,
        ]);

        return $dun;
    }

    public function getPathAttribute()
    {
        return $this->uri . '/' . $this->id;
    }

    public function settings()
    {
        return $this->hasOne('App\Receipts\Duns\Settings', 'dun_id');
    }

    public function invoice()
    {
        return $this->belongsTo('App\Receipts\Invoice', 'receipt_id');
    }

    public function setName()
    {

    }

    public function getNextLevelAttribute()
    {
        return Level::nextLevel($this->settings->level->level);
    }
}
