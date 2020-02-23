<?php

namespace App\Receipts\Abos;

use App\Receipts\Invoice;
use App\Receipts\Item as ReceiptItem;
use App\Receipts\Receipt;
use App\Receipts\Statuses\Created;
use App\Receipts\Statuses\MorphedTo;
use App\Receipts\Term;
use Illuminate\Database\Eloquent\Model;
use Parental\HasParent;

class Abo extends Receipt
{
    use HasParent;

    const AVAILABLE_STATUSES = [

    ];

    const LABEL_SINGULAR = 'Abo';
    const LABEL_PLURAL = 'Abos';
    const SLUG = 'abos';
    const URI = '/' . self::SLUG;

    const DEFAULT_INTERVAL = [
        'value' => 1,
        'unit' => 'months',
    ];

    protected $typeName = 'Abo';
    protected $uri = '/abos';

    public $dateName = 'Datum';

    public function childCreated()
    {
        $today = today();

        $this->settings()->create([
            'company_id' => $this->company_id,
            'active' => false,
            'interval_value' => static::DEFAULT_INTERVAL['value'],
            'interval_unit' => static::DEFAULT_INTERVAL['unit'],
            'start_at' => $today,
            'next_at' => $today,
        ]);

        $this->contacts()->attach($this->contact_id);
    }

    public function childDeleting()
    {
        $this->settings()->delete();
    }

    public static function from(Receipt $receipt) : self {

        $attributes = $receipt->getAttributes();
        unset(
            $attributes['address'],
            $attributes['date'],
            $attributes['date_due'],
            $attributes['latest_status_id'],
            $attributes['latest_status_type'],
            $attributes['number'],
            $attributes['subject'],
            $attributes['term_id'],
            $attributes['text_above'],
            $attributes['text_below'],
            $attributes['type']
        );

        $attributes['name'] = '';
        $abo = self::create($attributes);

        foreach ($receipt->items as $item) {
            $attributes = $item->getAttributes();
            $attributes['receipt_id'] = $abo->id;
            ksort($attributes);
            ReceiptItem::create($attributes);
        }

        $abo->cache();

        $morphedToStatus = new MorphedTo();
        $morphedToStatus->data = [
            'to_id' => $abo->id,
        ];
        $receipt->setStatus($morphedToStatus);

        $draftStatus = new Created();
        $draftStatus->data = [
            'from_id' => $receipt->id,
        ];
        $abo->setStatus($draftStatus);

        return $abo;
    }

    public function toInvoice()
    {
        $invoices = [];
        foreach ($this->contacts as $key => $contact) {
            $invoices[] = Invoice::from($this, [
                'contact' => $contact,
            ]);
        }

        $this->settings->setNextAt();

        return $invoices;
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

    public function getPathAttribute()
    {
        return $this->uri . '/' . $this->id;
    }

    public function contacts()
    {
        return $this->belongsToMany('App\Contacts\Contact', 'contact_receipt');
    }

    public function receipts()
    {
        return $this->hasMay('App\Receipts\Receipt', 'abo_id');
    }

    public function settings()
    {
        return $this->hasOne('App\Receipts\Abos\Settings', 'abo_id');
    }

    protected function getNameFormat()
    {
        return $this->company->abo_name_format;
    }

    public function draft(array $data = [])
    {
        if (! array_key_exists('date', $data))
        {
            $data['date'] = today();
        }
        (new Created())->associate($this, $data);
    }

}
