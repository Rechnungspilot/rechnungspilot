<?php

namespace App\Receipts;

use App\Receipts\Item as ReceiptItem;
use App\Receipts\Statuses\Accepted;
use App\Receipts\Statuses\Declined;
use App\Receipts\Statuses\Draft;
use App\Receipts\Statuses\Expired;
use App\Receipts\Statuses\MorphedTo;
use App\Receipts\Statuses\Send;
use App\Receipts\Statuses\Viewed;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Parental\HasParent;

class Quote extends Receipt
{
    use HasParent;

    const AVAILABLE_STATUSES = [
        Draft::class => Draft::NAME,
        Send::class => Send::NAME,
        Viewed::class => Viewed::NAME,
        Expired::class => Expired::NAME,
        Accepted::class => Accepted::NAME,
        Declined::class => Declined::NAME,
        MorphedTo::class => MorphedTo::NAME,
    ];

    const LABEL_SINGULAR = 'Angebot';
    const LABEL_PLURAL = 'Angebote';
    const SLUG = 'angebote';
    const URI = '/' . self::SLUG;

    protected $typeName = 'Angebot';
    protected $uri = self::URI;

    public $dateName = 'Angebotsdatum';
    public $dateDueName = 'Gültig bis';

    public static function nextNumber(Carbon $date)
    {
        return self::whereDate('date', $date)->max('number') + 1;
    }

    public static function from(Receipt $receipt) : self {

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

        $attributes['term_id'] = Term::default(self::class)->id;
        $quote = self::create($attributes);

        foreach ($receipt->items as $item) {
            $attributes = $item->getAttributes();
            $attributes['receipt_id'] = $quote->id;
            ksort($attributes);
            ReceiptItem::create($attributes);
        }

        $quote->cache();

        $morphedToStatus = new MorphedTo();
        $morphedToStatus->data = [
            'to_id' => $quote->id,
        ];
        $receipt->setStatus($morphedToStatus);

        return $quote;

    }

    public static function open()
    {
        $sql = "SELECT
                    COUNT(*) AS count,
                    SUM(receipts.outstanding) AS amount
                FROM
                    receipts
                WHERE
                    receipts.company_id = :company_id AND
                    receipts.type = :type AND
                    receipts.latest_status_type IN (:status_send, :status_viewed)";
        $data = DB::select($sql, [
            'company_id' => auth()->user()->company_id,
            'type' => self::class,
            'status_send' => Send::class,
            'status_viewed' => Viewed::class
        ]);

        return $data[0];
    }

    public function isInvoiceable() : bool
    {
        return $this->latest_status_type == Accepted::class;
    }

    public function isOrderable() : bool
    {
        return $this->latest_status_type == Accepted::class;
    }

    public function getNextMainStatusAttribute()
    {
        if ($this->nextMainStatus)
        {
            return $this->nextMainStatus;
        }

        switch ($this->latest_status_type) {
            case Send::class: $this->nextMainStatus = new Accepted(); break;
            case Expired::class: $this->nextMainStatus = new Declined(); break;
        }

        return $this->nextMainStatus;
    }

    public function getTypeNameAttribute() {

        if ($this->statuses()->where('type', Accepted::class)->exists())
        {
            return 'Auftragsbestätigung';
        }

        return $this->typeName;
    }

    public function getMailBoilerplateAttribute()
    {
        return Boilerplate::default(Boilerplate::STANDARD_QUOTE_MAIL, $this->company_id);
    }

    protected function getNameFormat()
    {
        return $this->company->quote_name_format;
    }

    protected function setTextAbove()
    {
        $this->text_above = Boilerplate::default(Boilerplate::STANDARD_QUOTE_ABOVE, $this->company_id);
    }

    protected function setTextBelow()
    {
        $this->text_below = Boilerplate::default(Boilerplate::STANDARD_QUOTE_BELOW, $this->company_id);
    }

    public function scopePossibleExpired($query)
    {
        return $query->whereIn('latest_status_type', [
            Send::class,
        ]);
    }
}
