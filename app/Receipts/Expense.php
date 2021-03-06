<?php

namespace App\Receipts;

use App\Receipts\Item as ReceiptItem;
use App\Receipts\Receipt;
use App\Receipts\Statuses\Draft;
use App\Receipts\Statuses\Expensed;
use App\Receipts\Statuses\MorphedTo;
use App\Receipts\Statuses\Overdue;
use App\Receipts\Statuses\Payed;
use App\Receipts\Statuses\Payment;
use App\Receipts\Statuses\Received;
use App\Receipts\Statuses\Send;
use App\Receipts\Statuses\Viewed;
use App\Receipts\Term;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Parental\HasParent;

class Expense extends Receipt
{
    use HasParent,
        HasLabels,
        HasModelPath;

    const AVAILABLE_STATUSES = [
        'outstanding' => 'Offen',
        Draft::class => Draft::NAME,
        Received::class => Received::NAME,
        Viewed::class => Viewed::NAME,
        Overdue::class => Overdue::NAME,
        Payment::class => Payment::NAME,
        Payed::class => Payed::NAME,
        Expensed::class => Expensed::NAME,
    ];

    const ROUTE_NAME = 'receipts.expenses';
    const TYPE = 'expenses';

    const LABEL_SINGULAR = 'Ausgabe';
    const LABEL_PLURAL = 'Ausgaben';
    const SLUG = 'ausgaben';
    const URI = '/' . self::SLUG;

    protected $typeName = 'Ausgabe';
    protected $uri = self::URI;

    public $dateName = 'Rechnungsdatum';
    public $dateDueName = 'Fälligkeitsdatum';

    public static function from(Receipt $receipt, array $parameters = []) : self {

        $contact = Arr::get($parameters, 'contact');
        $credit = Arr::get($parameters, 'credit', false);
        $receipt_id = Arr::get($parameters, 'receipt_id');
        $receipt_item_ids = Arr::get($parameters, 'receipt_item_ids');

        $attributes = $receipt->getAttributes();
        unset(
            $attributes['type'],
            $attributes['number'],
            $attributes['subject'],
            $attributes['date'],
            $attributes['date_due'],
            $attributes['address'],
            $attributes['text_above'],
            $attributes['text_below'],
            $attributes['latest_status_type'],
            $attributes['latest_status_id']
        );

        if ($contact) {
            $attributes['contact_id'] = $contact->id;
            $attributes['address'] = $contact->billing_address;
        }

        if (in_array(get_class($receipt), [Abo::class, Order::class])) {
            $attributes['receipt_id'] = $receipt->id;
        }

        $attributes['name'] = '';
        $attributes['term_id'] = Term::default(self::class)->id;
        $expense = self::create($attributes);

        foreach ($receipt->items as $item) {
            $attributes = $item->getAttributes();
            if ($credit) {
                $attributes['quantity'] *= -1;
            }
            $attributes['receipt_id'] = $expense->id;
            ksort($attributes);
            ReceiptItem::create($attributes);
        }

        $expense->cache();

        $morphedToStatus = new MorphedTo();
        $morphedToStatus->data = [
            'to_id' => $expense->id,
        ];
        $receipt->setStatus($morphedToStatus);

        $draftStatus = new Draft();
        $draftStatus->data = [
            'from_id' => $receipt->id,
        ];
        $expense->setStatus($draftStatus);

        if ($credit) {
            $paymentStatus = new Payment();
            $paymentStatus->data = [
                'receipt_id' => $expense->id,
                'amount' => $expense->net * -1,
            ];
            $receipt->setStatus($paymentStatus);
        }

        return $expense;

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
                'singular' => 'Ausgabe',
                'plural' => 'Ausgaben',
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
            case Draft::class: $this->nextMainStatus = new Received(); break;
            case Received::class: $this->nextMainStatus = new Payment(); break;
            case Viewed::class: $this->nextMainStatus = new Payment(); break;
            case Overdue::class: $this->nextMainStatus = new Payment(); break;
            case Payment::class: $this->nextMainStatus = new Payment(); break;
            case Payed::class: $this->nextMainStatus = new Expensed(); break;
        }

        return $this->nextMainStatus;
    }

    public function getRouteParameterAttribute() : array
    {
        return [
            'expense' => $this->id,
        ];
    }

    public function setName()
    {

    }

    public function previewFile()
    {
        return $this->morphOne('App\Userfile', 'fileable');
    }
}
