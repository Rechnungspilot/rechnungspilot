<?php

namespace App\Receipts\Abos;

use App\Receipts\Invoice;
use App\Receipts\Item as ReceiptItem;
use App\Receipts\Receipt;
use App\Receipts\Statuses\Created;
use App\Receipts\Statuses\MorphedTo;
use App\Receipts\Term;
use App\Support\Type;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Parental\HasParent;

class Abo extends Receipt
{
    use HasParent,
        HasLabels,
        HasModelPath;

    const AVAILABLE_STATUSES = [

    ];

    const ROUTE_NAME = 'receipts.subscriptions';
    const TYPE = 'subscriptions';

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

    public $settings_type;

    public function childCreated()
    {
        $today = today();

        $this->settings()->create([
            'type' => $this->settings_type,
            'company_id' => $this->company_id,
            'active' => false,
            'interval_value' => static::DEFAULT_INTERVAL['value'],
            'interval_unit' => static::DEFAULT_INTERVAL['unit'],
            'start_at' => $today,
            'next_at' => $today,
        ]);
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

    public static function indexPath(array $attributes = []) : string
    {
        return route(self::ROUTE_NAME . '.index', [
            'type' => $attributes['settings_type'],
        ]);
    }

    protected static function labels() : array
    {
        return [
            'nominativ' => [
                'singular' => 'Abo',
                'plural' => 'Abos',
            ],
        ];
    }

    public function toReceipt()
    {
        $receipts = [];
        foreach ($this->contacts as $key => $contact) {
            $receipt = $this->settings->type::from($this, [
                'contact' => $contact,
            ]);
            if ($this->settings->send_mail == 1) {
                $receipt->send();
            }
            $receipts[] = $receipt;
        }

        $this->settings->setNextAt();

        return $receipts;
    }

    public function toInvoice()
    {
        $invoices = [];
        foreach ($this->contacts as $key => $contact) {
            $invoice = Invoice::from($this, [
                'contact' => $contact,
            ]);
            if ($this->settings->send_mail == 1) {
                $invoice->send();
            }
            $invoices[] = $invoice;
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

    public function getRouteParameterAttribute() : array
    {
        return [
            'type' => Type::type((is_null($this->settings) ? $this->settings_type : $this->settings->type)),
            'subscription' => $this->id,
        ];
    }

    public function getContactLinkStringAttribute() : string
    {
        return $this->contacts->implode('link', ', ');
    }

    public function setSettingsTypeAttribute($value)
    {
        $this->settings_type = $value;
    }

    public function contacts()
    {
        return $this->belongsToMany('App\Contacts\Contact', 'contact_receipt')
            ->using(\App\Models\Receipts\Contact::class)
            ->withTimestamps();
    }

    public function receipts()
    {
        return $this->hasMay('App\Receipts\Receipt', 'abo_id');
    }

    public function settings() : HasOne
    {
        return $this->hasOne(\App\Receipts\Abos\Settings::class, 'abo_id');
    }

    protected function getNameFormat()
    {
        return $this->company->abo_name_format;
    }

    public function draft(array $data = [])
    {
        if (! array_key_exists('date', $data)) {
            $data['date'] = today();
        }
        (new Created())->associate($this, $data);
    }

}
