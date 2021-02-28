<?php

namespace App\Receipts;

use App\Company;
use App\Contacts\Interaction;
use App\Item;
use App\Receipts\Expense;
use App\Receipts\Income;
use App\Receipts\Item as ReceiptItem;
use App\Receipts\Statuses\Completed;
use App\Receipts\Statuses\Draft;
use App\Receipts\Statuses\Overdue;
use App\Receipts\Statuses\Payed;
use App\Receipts\Statuses\Payment;
use App\Receipts\Statuses\Send;
use App\Receipts\Statuses\Status;
use App\Receipts\Statuses\Viewed;
use App\Traits\HasComments;
use App\Traits\HasCompany;
use App\Traits\HasCustomFields;
use App\Traits\HasTags;
use App\Traits\HasUserfiles;
use App\Transaction;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PDF;
use \Parental\HasChildren;

class Receipt extends Model
{
    use HasComments, HasCustomFields, HasCompany, HasChildren, HasTags, HasUserfiles;

    const SLUG = 'belege';

    protected $availableStatuses = [];
    protected $tax = [];
    protected $typeName = 'Nicht gesetzt';
    protected $nextMainStatus = null;
    protected $uri = 'belege';

    protected $appends = [
        'path',
        'tagsString',
        'tags_badges',
        'typeName',
        'dateName',
        'dateDueName',
        'numberLabel',
        'uri',
        'labelSingular',
        'labelPlural',
        'contact_link_string',
    ];

    protected $casts = [
        'date' => 'date',
        'date_due' => 'date',
    ];

    protected $fillable = [
        'address',
        'company_id',
        'contact_id',
        'date',
        'date_due',
        'final_invoice_id',
        'is_partial',
        'latest_status_id',
        'latest_status_type',
        'name',
        'net',
        'number',
        'receipt_id',
        'subject',
        'tax',
        'term_id',
        'text',
        'text_above',
        'text_below',
        'type',
        'settings_type',
    ];

    public $dateName = 'Datum';
    public $dateDueName = '';

    const LABEL_SINGULAR = 'Nicht gesetzt';
    const LABEL_PLURAL = 'Nicht gesetzt';
    const URI = 'Nicht gesetzt';

    /**
     * The booting method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            if (! $model->date) {
                $model->date = now()->startOfDay();
            }

            if (is_null($model->number)) {
                $model->number = self::nextNumber($model->date);
            }
            $model->setName();

            if (! $model->date_due) {
                $model->date_due = now()->startOfDay();
            }
            if (! $model->term_id)
            {
                $term = Term::default(get_class($model));
                $model->term_id = is_null($term) ? null : $term->id;
            }

            $model->setUUID();

            $model->setTextAbove();
            $model->setTextBelow();

            return true;
        });

        static::created(function($model)
        {
            $model->draft();
            $model->childCreated();

            return true;
        });

        static::updating(function($model)
        {
            $model->setName();

            return true;
        });

        static::deleting(function($model)
        {
            $model->statuses()->delete();
            $model->childDeleting();

            return true;
        });
    }

    public function childCreated()
    {

    }

    public function childDeleting()
    {

    }



    /**
     * @param $name
     * @param $flag
     * @return bool
     */
    public function hasFlag($flag) : bool
    {
        return (($this->flags & $flag) == $flag);
    }

    /**
     * @param string flag
     * @param int $flag
     * @param $value
     * @return $this
     */
    public function setFlag($flag, $value) : self
    {
        if ($value) {
            $this->flags |= $flag;
        } else {
            $this->flags &= ~$flag;
        }

        return $this;
    }

    public static function nextNumber(Carbon $date)
    {
        return self::whereYear('date', $date->year)->max('number') + 1;
    }

    public static function revenueByMonth(int $companyId, int $contactId = 0)
    {
        $from = now()->sub('11', 'months')->startOf('month');
        $to = now()->endOf('month');
        $periods = new CarbonPeriod($from, '1 months', $to);

        $sql = "SELECT
                    YEAR(receipts.date) AS year,
                    MONTH(receipts.date) AS month,
                    SUM(receipts.gross-receipts.outstanding) AS payed,
                    SUM(receipts.outstanding) AS outstanding
                FROM
                    receipts
                WHERE
                    receipts.company_id = :company_id AND
                    (
                        receipts.type = :type_income OR
                        receipts.type = :type_invoice
                    ) AND
                    " . ($contactId > 0 ? ' receipts.contact_id = :contact_id AND ' : '') . "
                    receipts.date BETWEEN :from AND :to
                GROUP BY
                    YEAR(receipts.date),
                    MONTH(receipts.date)";

        $params = [
            'company_id' => $companyId,
            'from' => $from,
            'to' => $to,
            'type_income' => Income::class,
            'type_invoice' => Invoice::class,
        ];
        if ($contactId > 0) {
            $params['contact_id'] = $contactId;
        }
        $data = DB::select($sql, $params);
        $revenues = [];
        foreach ($data as $key => $revenue) {
            $key = $revenue->year . str_pad($revenue->month, 2, '0', STR_PAD_LEFT);
            $revenues[$key] = [
                'key' => $key,
                'payed' => $revenue->payed / 100,
                'outstanding' => (float) $revenue->outstanding / 100,
                'expenses' => 0,
            ];
        }

        $sql = "SELECT
                    YEAR(receipts.date) AS year,
                    MONTH(receipts.date) AS month,
                    SUM(receipts.gross) AS gross
                FROM
                    receipts
                WHERE
                    receipts.company_id = :company_id AND
                    receipts.type = :type AND
                    " . ($contactId > 0 ? ' receipts.contact_id = :contact_id AND ' : '') . "
                    receipts.date BETWEEN :from AND :to
                GROUP BY
                    YEAR(receipts.date),
                    MONTH(receipts.date)";

        $params = [
            'company_id' => $companyId,
            'from' => $from,
            'to' => $to,
            'type' => Expense::class,
        ];
        if ($contactId > 0) {
            $params['contact_id'] = $contactId;
        }
        $data = DB::select($sql, $params);
        foreach ($data as $key => $revenue) {
            $key = $revenue->year . str_pad($revenue->month, 2, '0', STR_PAD_LEFT);
            if (array_key_exists($key, $revenues)) {
                $revenues[$key]['expenses'] = (float) $revenue->gross / 100;
            }
            else {
                $revenues[$key] = [
                    'key' => $key,
                    'payed' => 0,
                    'outstanding' => 0,
                    'expenses' => $revenue->gross / 100,
                ];
            }
        }

        foreach ($periods as $period) {
            $key = $period->format('Ym');
            if (array_key_exists($key, $revenues)) {
                continue;
            }
            $revenues[$key] = [
                'key' => $key,
                'gross' => 0,
                'net' => 0,
                'tax_value' => 0,
                'outstanding' => 0,
                'expenses' => 0,
            ];
        }
        krsort($revenues, SORT_NUMERIC);

        return $revenues;
    }

    public static function labels()
    {
        return [
            'uri' => static::URI,
            'singular' => static::LABEL_SINGULAR,
            'plural' => static::LABEL_PLURAL,
        ];
    }

    public function contact()
    {
        return $this->belongsTo('App\Contacts\Contact');
    }

    public function interactions() : MorphMany
    {
        return $this->morphMany(Interaction::class, 'interactionable');
    }

    public function items()
    {
        return $this->hasMany('App\Receipts\Item')
            ->with('unit');
    }

    public function order()
    {
        return $this->belongsTo(self::class, 'receipt_id');
    }

    public function status()
    {
        return $this->hasOne('App\Receipts\Statuses\Status', 'id', 'latest_status_id');
    }

    public function statuses()
    {
        return $this->hasMany('App\Receipts\Statuses\Status')->with('transaction');
    }

    public function payments()
    {
        return $this->hasMany('App\Receipts\Statuses\Payment')->with('transaction');
    }

    public function payedStatus()
    {
        return $this->hasOne('App\Receipts\Statuses\Payed');
    }

    public function term()
    {
        return $this->belongsTo('App\Receipts\Term');
    }

    public function todos()
    {
        return $this->morphMany('App\Todos\Todo', 'todoable');
    }

    public function template()
    {
        return $this->hasOne('App\Templates\Template', 'company_id', 'company_id');
    }

    public function addItem(Item $item, array $attributes = [], Model $receiptable = null) : ReceiptItem
    {
        $receiptItem = ReceiptItem::make([
            'receipt_id' => $this->id,
            'company_id' => $this->company_id,
            'item_id' => $item->id,
            'unit_id' => $item->unit_id,
            'name' => $item->name,
            'description' => $item->description,
            'quantity' => $attributes['quantity'] ?? 1,
            'discount' => 0,
            'tax' => $item->tax,
            'unit_price' => $attributes['unit_price'] ?? $item->unit_price,
        ]);

        if (! is_null($receiptable))
        {
            $receiptItem->receiptable()->associate($receiptable);
        }

        $receiptItem->save();

        return $receiptItem;
    }

    public function delItem(ReceiptItem $receiptItem) : self
    {
        $receiptItem->delete();

        return $this;
    }

    public function addSet()
    {
        // TODO
    }

    public function cache() {

        $this->calculateTax();
        $this->calculateOutstanding();

        $this->update();

    }

    public function isDeletable() : bool
    {
        return true;
    }

    public function isOverDue()
    {
        return $this->statuses->contains('type', Overdue::class);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getUriAttribute()
    {
        return $this->uri;
    }

    public function getMailBoilerplateAttribute()
    {
        return '';
    }

    public function getDateDueForHumansAttribute()
    {
        $now = now();
        return ($this->date_due->diff($now)->days < 1) ? 'heute' : $this->date_due->diffForHumans();
    }

    public function getLabelSingularAttribute()
    {
        return static::LABEL_SINGULAR;
    }

    public function getLabelPluralAttribute()
    {
        return static::LABEL_PLURAL;
    }

    public function getNumberLabelAttribute()
    {
        return 'Number Label nicht gesetzt';
    }

    public function getPathAttribute()
    {
        return '/' . static::SLUG . '/' . $this->id . '/edit';
    }

    public function getNextMainStatusAttribute()
    {
        return $this->nextMainStatus;
    }

    public function getContactLinkStringAttribute() : string
    {
        return ($this->contact_id ? $this->contact->link : '');
    }

    public function getTypeNameAttribute()
    {
        return $this->typeName;
    }

    public function getDateNameAttribute()
    {
        return $this->dateName;
    }

    public function getDateDueNameAttribute()
    {
        return $this->dateDueName;
    }

    public function getTaxAttribute()
    {
        return $this->tax;
    }

    public function getAvailableStatusesAttribute()
    {
        return $this->availableStatuses;
    }

    public function getFilenameAttribute()
    {
        return $this->typeName . ' ' . $this->name . '.pdf';
    }

    public function getOutstandingBalanceAttribute()
    {
        return $this->attributes['outstanding'];
    }

    public function calculateTax()
    {
        $this->attributes['gross'] = 0;
        $this->attributes['net'] = 0;
        $this->attributes['tax_value'] = 0;
        foreach ($this->items as $receiptItem) {
            $key = (int)($receiptItem->tax * 100);
            if (!array_key_exists($key, $this->tax))
            {
                $this->tax[$key] = [
                    'tax' => $receiptItem->tax,
                    'value' => 0,
                    'net' => 0,
                ];

            }
            $amount = $receiptItem->net * $receiptItem->tax;
            $this->tax[$key]['value'] += $amount;
            $this->tax[$key]['net'] += $receiptItem->net;
            $this->attributes['tax_value'] += $amount;
            $this->attributes['gross'] += $receiptItem->gross;
            $this->attributes['net'] += $receiptItem->net;
        }
    }

    public function calculateOutstanding() {
        $outstandingBalance = $this->gross;
        foreach ($this->payments()->get() as $payment) {
            $outstandingBalance -= $payment->amount;
        }

        $this->attributes['outstanding'] = $outstandingBalance;

        return $outstandingBalance;
    }

    public function setStatus(Status $status)
    {
        $this->statuses()->save($status);
        if (! $this->latest_status_type || ($status::RANK >= $this->latest_status_type::RANK))
        {
            $this->latest_status_id = $status->id;
            $this->latest_status_type = get_class($status);
            $this->save();
        }
    }

    public function completed()
    {
        (new Completed())->associate($this, [
            'date' => today(),
        ]);
    }

    public function isPayed() : bool
    {
        return $this->calculateOutstanding() <= 0;
    }

    public function paid(Carbon $payedAt)
    {
        (new Payed())->associate($this, [
            'date' => $payedAt,
            'outstanding' => $this->calculateOutstanding(),
        ]);
    }

    public function pay(int $accountId, float $amount, bool $completed, int $transactionId = 0)
    {
        (new Payment())->associate($this, [
            'account_id' => $accountId,
            'amount' => $amount,
            'completed' => $completed,
            'date' => today(),
            'transaction_id' => $transactionId,
        ]);
    }

    public function sendWithoutMail()
    {
        (new Send())->associate($this, [
            'send_mail' => 0,
            'date' => today(),
            'email' => '',
            'text' => '',
        ]);
    }

    public function send(string $to = '', string $body = '', string $bcc = '')
    {
        (new Send())->associate($this, [
            'send_mail' => 1,
            'date' => today(),
            'email' => $to ?: $this->contact->defaultReceiptEmail('invoice'),
            'text' => $body ?: $this->mailBoilerplate,
        ]);
    }

    public function draft(array $data = [])
    {
        if (! array_key_exists('date', $data))
        {
            $data['date'] = today();
        }
        (new Draft())->associate($this, $data);
    }

    public function delStatus(Status $status)
    {
        if ($status->type == Payment::class)
        {
            if ($payedStatus = $this->hasStatus(Payed::class))
            {
                $this->delStatus($payedStatus);
            }
        }

        $isLatestStatus = (is_null($this->status) || $this->status->is($status));
        $status->delete();

        if ($isLatestStatus == false)
        {
            return;
        }

        $this->setLatestStatus();

        $this->cache();
    }

    public function setLatestStatus()
    {
        $latestStatus = null;
        $maxRank = 0;

        $this->refresh();

        foreach ($this->statuses as $status)
        {
            if ($status::RANK > $maxRank)
            {
                $latestStatus = $status;
            }
            $maxRank = max($maxRank, $status->RANK);
        }

        $this->latest_status_type = get_class($latestStatus);
        $this->latest_status_id = $latestStatus->id;
        $this->update();
    }

    public function hasStatus(string $type)
    {
        return $this->statuses()->where('type', $type)->get()->first();
    }

    protected function getNameFormat()
    {
        return '';
    }

    public function setName()
    {
        if ($this->number == 0) {
            $this->name = 'Vorläufig';
            return;
        }

        // Parameter string $format replace Placeholder
        $placeholder = [
            '#NUMMER#',
            '#NUMMER-2#',
            '#NUMMER-3#',
            '#NUMMER-4#',
            '#DATUM-J#',
            '#DATUM-JJ#',
            '#DATUM-M#',
            '#DATUM-MM#',
            '#DATUM-T#',
            '#DATUM-TT#',
        ];
        $this->name = str_replace($placeholder, [ $this->number, str_pad($this->number, 2, '0', STR_PAD_LEFT), str_pad($this->number, 3, '0', STR_PAD_LEFT), str_pad($this->number, 4, '0', STR_PAD_LEFT), $this->date->format('y'), $this->date->year, $this->date->format('n'), $this->date->format('m'), $this->date->day, $this->date->format('d')], $this->getNameFormat());
    }

    protected function setTextAbove()
    {
        $this->text_above = null;
    }

    protected function setTextBelow()
    {
        $this->text_below = null;
    }

    public function formatBoilerplate(string $text) : string{
        return str_replace(array_keys(Boilerplate::PLACEHOLDER), [ $this->date->format('d.m.Y'), $this->contact->name ], $text);
    }

    public function scopeContact(Builder $query, $id) : Builder
    {
        if ($id == 0) {
            return $query;
        }

        return $query->where('contact_id', $id);
    }

    public function scopeSearch($query, $searchtext)
    {
        if ($searchtext == '') {
            return $query;
        }

        return $query->where( function ($query) use($searchtext)
        {
            $query
                ->orWhere('name', 'LIKE', '%' . $searchtext . '%')
                ->orWhere('number', 'LIKE', '%' . $searchtext . '%')
                ->orWhere('net', 'LIKE', '%' . $searchtext . '%')
                ->orWhere('gross', 'LIKE', '%' . $searchtext . '%');
        });
    }

    public function scopeStatus($query, $status)
    {
        if (is_null($status) || $status == '0') {
            return $query;
        }
        elseif ($status == 'outstanding')
        {
            return $query->whereIn('latest_status_type', [
                Send::class,
                Viewed::class,
                Overdue::class,
                Payment::class,
            ]);
        }

        return $query->where('latest_status_type', $status);
    }

    public function scopeInvoices($query)
    {
        return $query->whereIn('type', self::INVOICE_TYPES);
    }

    public function scopeHasFlags(Builder $query, string $status, $value) : Builder
    {
        if (is_null($value)) {
            return $query;
        }

        if ($value) {
            return $query->whereRaw('flags & :status <> 0', [
                'status' => $status,
            ]);
        }

        return $query->whereRaw('flags & :status == 0', [
            'status' => $status,
        ]);
    }

    public function pdf()
    {
        $this->load(['items', 'term']);
        $this->calculateTax();

        return PDF::loadView('receipt.stubs.' . $this->template->stub, [
            'receipt' => $this->load(['partialinvoices']),
            'company' => Company::findOrFail($this->company_id),
            'template' => $this->template,
        ], [], [
            'margin_top' => 120,
            'margin_bottom' => 20,
            'margin_header' => 20,
        ]);
    }

    protected function checkUUID(string $uuid)
    {
        return Receipt::where('uuid', $uuid)->exists();
    }

    protected function setUUID()
    {
        $uuid = Str::uuid();
        if ($this->checkUUID($uuid))
        {
            $this->setUUID();
        }

        $this->uuid = $uuid;
    }

}
