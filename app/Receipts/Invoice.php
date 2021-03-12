<?php

namespace App\Receipts;

use App\Contacts\Contact;
use App\Receipts\Abos\Abo;
use App\Receipts\Boilerplate;
use App\Receipts\Dun;
use App\Receipts\Duns\Level;
use App\Receipts\Duns\Settings;
use App\Receipts\Item as ReceiptItem;
use App\Receipts\Statuses\Draft;
use App\Receipts\Statuses\Expensed;
use App\Receipts\Statuses\MorphedTo;
use App\Receipts\Statuses\Overdue;
use App\Receipts\Statuses\Payed;
use App\Receipts\Statuses\Payment;
use App\Receipts\Statuses\Send;
use App\Receipts\Statuses\Viewed;
use App\Receipts\Term;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Parental\HasParent;

class Invoice extends Receipt
{
    use HasParent,
        HasLabels,
        HasModelPath;

    // Key durch Status::FLAG ersetzen
    const AVAILABLE_STATUSES = [
        'outstanding' => 'Offen',
        Draft::class => Draft::NAME,
        Send::class => Send::NAME,
        Viewed::class => Viewed::NAME,
        Overdue::class => Overdue::NAME,
        Payment::class => Payment::NAME,
        Payed::class => Payed::NAME,
        Expensed::class => Expensed::NAME,
    ];

    const LABEL_SINGULAR = 'Rechnung';
    const LABEL_PLURAL = 'Rechnungen';
    const SLUG = 'rechnungen';
    const ROUTE_NAME = 'receipts.invoices';
    const TYPE = 'invoices';
    const URI = '/' . self::SLUG;

    protected $typeName = 'Rechnung';
    protected $uri = self::URI;

    public $dateName = 'Rechnungsdatum';
    public $dateDueName = 'Fälligkeitsdatum';

    public static function from(Receipt $receipt, array $parameters = []) : self {

        $contact = Arr::get($parameters, 'contact');
        $credit = Arr::get($parameters, 'credit', false);
        $receiptId = Arr::get($parameters, 'receipt_id');
        $receiptItemIds = Arr::get($parameters, 'receipt_item_ids');

        if ($receiptId)
        {
            $invoice = self::find($receiptId);
        }
        else
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
            if ($contact)
            {
                $attributes['contact_id'] = $contact->id;
                $attributes['address'] = $contact->billing_address;
            }

            if (in_array(get_class($receipt), [Abo::class, Order::class]))
            {
                $attributes['receipt_id'] = $receipt->id;
            }

            $attributes['term_id'] = Term::default(self::class)->id;
            $invoice = self::create($attributes);

            $invoice->status->data = [
                'from_id' => $receipt->id,
            ];
            $invoice->status->save();
        }

        $items = is_null($receiptItemIds) ? $receipt->items : $receipt->items->whereIn('id', $receiptItemIds);
        foreach ($items as $item) {
            $attributes = $item->getAttributes();
            if ($credit)
            {
                $attributes['quantity'] *= -1;
            }
            $attributes['receipt_id'] = $invoice->id;
            ksort($attributes);
            $receiptItem = ReceiptItem::make($attributes);
            $receiptItem->receiptable()->associate($item);
            $receiptItem->save();
        }

        $invoice->cache();

        $morphedToStatus = new MorphedTo();
        $morphedToStatus->company_id = $invoice->company_id;
        $morphedToStatus->data = [
            'to_id' => $invoice->id,
        ];
        $receipt->setStatus($morphedToStatus);

        if ($credit)
        {
            $paymentStatus = new Payment();
            $paymentStatus->company_id = $invoice->company_id;
            $paymentStatus->data = [
                'receipt_id' => $invoice->id,
                'amount' => $invoice->net * -1,
            ];
            $receipt->setStatus($paymentStatus);
        }

        return $invoice;

    }

    public static function outstandingBalance()
    {
        $sql = "SELECT
                    COUNT(*) AS count,
                    SUM(receipts.outstanding) AS amount
                FROM
                    receipts
                WHERE
                    receipts.company_id = :company_id AND
                    receipts.type = :type AND
                    receipts.latest_status_type IN (:status_send, :status_viewed, :status_overdue, :status_payment)";
        $data = DB::select($sql, [
            'company_id' => auth()->user()->company_id,
            'type' => self::class,
            'status_send' => Send::class,
            'status_viewed' => Viewed::class,
            'status_overdue' => Overdue::class,
            'status_payment' => Payment::class,
        ]);

        return $data[0];
    }

    protected static function labels() : array
    {
        return [
            'nominativ' => [
                'singular' => 'Rechnung',
                'plural' => 'Rechnungen',
            ],
        ];
    }

    public function getNextMainStatusAttribute()
    {
        if ($this->nextMainStatus)
        {
            return $this->nextMainStatus;
        }

        switch ($this->latest_status_type) {
            case Send::class: $this->nextMainStatus = new Payment(); break;
            case Viewed::class: $this->nextMainStatus = new Payment(); break;
            case Overdue::class: $this->nextMainStatus = new Payment(); break;
            case Payment::class: $this->nextMainStatus = new Payment(); break;
            case Payed::class: $this->nextMainStatus = new Expensed(); break;
        }

        return $this->nextMainStatus;
    }

    public function getTypeNameAttribute()
    {
        if ($this->net < 0)
        {
            return 'Gutschrift';
        }

        if ($this->is_partial) {
            return 'Abschlagsrechnung';
        }

        if (count($this->partialinvoices) > 0) {
            return 'Schlussrechnung';
        }

        return $this->typeName;
    }

    public function getPossiblePartialsAttribute()
    {
        return self::where('contact_id', $this->contact_id)
            ->where('is_partial', true)
            ->where('type', self::class)
            ->where('id', '!=', $this->id)
            ->get();
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'invoice' => $this->id,
        ];
    }

    public function finalinvoice() {

        return $this->belongsTo('App\Receipts\Invoice', 'final_invoice_id');

    }

    public function partialinvoices() {

        return $this->hasMany('App\Receipts\Invoice', 'final_invoice_id');

    }

    public function findLatestDun()
    {
        return $this->duns()
            ->join('dun_settings', 'receipts.id', 'dun_settings.dun_id')
            ->join('dun_levels', 'dun_levels.id', 'dun_settings.level_id')
            ->orderBy('dun_levels.level', 'DESC')
            ->first('receipts.*');
    }

    public function latestDun() {

        return $this->belongsTo('App\Receipts\Dun', 'latest_dun_id');

    }

    public function duns()
    {
        return $this->hasMany('App\Receipts\Dun', 'receipt_id');
    }

    public function getNextDunLevelAttribute()
    {
        return $this->latest_dun_id ? $this->latestDun->nextLevel : Level::nextLevel();
    }

    public function dun() : Dun
    {
        $level = $this->nextDunLevel;
        if (is_null($level))
        {
            // TODO: throw Exception
            return null;
        }

        $dun = Dun::from($this, $level);

        $this->latest_dun_id = $dun->id;
        $this->save();

        return $dun;
    }

    public function isDunable()
    {
        return ($this->isOverdue() && ($this->latest_dun_id == 0 || ($this->latest_dun_id > 0 && ! is_null($this->latestDun->nextLevel))));
    }

    public function getMailBoilerplateAttribute()
    {
        return Boilerplate::default(Boilerplate::STANDARD_INVOICE_MAIL, $this->company_id);
    }

    public function getNumberLabelAttribute()
    {
        return 'Rechnungsnummer';
    }

    protected function getNameFormat()
    {
        return $this->company->invoice_name_format;
    }

    protected function setTextAbove()
    {
        $this->text_above = Boilerplate::default(Boilerplate::STANDARD_INVOICE_ABOVE, $this->company_id);
    }

    protected function setTextBelow()
    {
        $this->text_below = Boilerplate::default(Boilerplate::STANDARD_INVOICE_BELOW, $this->company_id);
    }

    public function scopeOutstanding($query)
    {
        return $query->whereIn('latest_status_type', [
            Send::class,
            Viewed::class,
            Overdue::class,
            Payment::class,
        ]);
    }

    public function scopeDunable($query)
    {
        return $query->whereHas('statuses', function ($query) {
            $query->where('type', Overdue::class);
        });
    }

    public function scopeOverdue($query)
    {
        return $query->whereHas('statuses', function ($query) {
            $query->where('type', Overdue::class);
        });
    }

    public function scopePossibleOverdue($query)
    {
        return $query->whereIn('latest_status_type', [
            Send::class,
            Viewed::class,
            Payment::class,
        ]);
    }

}
