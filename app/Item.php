<?php

namespace App;

use App\Receipts\Abos\Abo;
use App\Receipts\Income;
use App\Receipts\Invoice;
use App\Receipts\Statuses\Draft;
use App\Scopes\HasCompanyScope;
use App\Traits\HasComments;
use App\Traits\HasCompany;
use App\Traits\HasCustomFields;
use App\Traits\HasTags;
use App\Unit;
use Carbon\CarbonPeriod;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Item extends Model
{
    use HasComments,
        HasCompany,
        HasCustomFields,
        HasLabels,
        HasModelPath,
        HasTags;

    const DECIMALS = [0,1,2,3];

    const DEFAULT_NAME = 'Neuer Artikel';

    const TYPE_ITEM = 0;
    const TYPE_SERVICE = 1;

    const PRICE_DECIMALS = 6;

    const ROUTE_NAME = 'items';

    const TYPES = [
        self::TYPE_ITEM => 'Artikel',
        self::TYPE_SERVICE => 'Dienstleistung',
    ];

    public $uri = '/artikel';

    protected $appends = [
        'tagsString',
        'path',
        'tags_badges',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:' . self::PRICE_DECIMALS,
        'unit_price' => 'decimal:' . self::PRICE_DECIMALS,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'cost_center',
        'decimals',
        'description',
        'duration',
        'name',
        'number',
        'expense_account_number',
        'revenue_account_number',
        'tax',
        'type',
        'unit_cost',
        'unit_id',
        'unit_price',
    ];

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
            $model->decimals = 2;

            if (! $model->company_id)
            {
                $model->company_id = auth()->user()->company_id;
            }

            if (! $model->name)
            {
                $model->name = self::DEFAULT_NAME;
            }

            return true;
        });

        static::created(function($model)
        {
            $model->prices()->create([
                'company_id' => $model->company_id,
                'start_at' => '1970-01-01',
            ]);

            return true;
        });

        static::updating(function($model)
        {
            if ($model->isDirty(['unit_cost', 'unit_price']))
            {
                $model->prices()->create([
                    'company_id' => $model->company_id,
                    'start_at' => today(),
                    'unit_cost' => $model->unit_cost,
                    'unit_price' => $model->unit_price,
                ]);
            }

            return true;
        });
    }

    public static function labels() : array
    {
        return [
            'nominativ' => [
                'singular' => 'Artikel',
                'plural' => 'Artikel',
            ],
        ];
    }

    public function revenueByMonth()
    {
        $from = now()->sub('11', 'months')->startOf('month');
        $to = now()->endOf('month');
        $periods = new CarbonPeriod($from, '1 months', $to);

        $sql = "SELECT
                    YEAR(receipts.date) AS year,
                    MONTH(receipts.date) AS month,
                    SUM(item_receipt.gross) AS revenue,
                    AVG(item_receipt.unit_price) AS price,
                    SUM(item_receipt.quantity) AS quantity
                FROM
                    item_receipt,
                    receipts
                WHERE
                    item_receipt.item_id = :item_id AND
                    item_receipt.company_id = :company_id AND
                    receipts.id = item_receipt.receipt_id AND
                    (
                        receipts.type = :type_income OR
                        receipts.type = :type_invoice
                    ) AND
                    receipts.date BETWEEN :from AND :to
                GROUP BY
                    YEAR(receipts.date),
                    MONTH(receipts.date)";

        $data = DB::select($sql, [
            'item_id' => $this->id,
            'company_id' => $this->company_id,
            'from' => $from,
            'to' => $to,
            'type_income' => Income::class,
            'type_invoice' => Invoice::class,
        ]);
        $revenues = [];
        foreach ($data as $key => $revenue) {
            $key = $revenue->year . str_pad($revenue->month, 2, '0', STR_PAD_LEFT);
            $revenues[$key] = [
                'key' => $key,
                'revenue' => $revenue->revenue / 100,
                'price' => (float) $revenue->price,
                'quantity' => (float) $revenue->quantity,
            ];
        }

        foreach ($periods as $period) {
            $key = $period->format('Ym');
            if (array_key_exists($key, $revenues))
            {
                continue;
            }
            $revenues[$key] = [
                'key' => $key,
                'revenue' => 0,
                'price' => 0,
                'quantity' => 0,
            ];
        }
        krsort($revenues, SORT_NUMERIC);

        return $revenues;
    }

    public function cache()
    {
        $this->calculateRevenue();

        $this->save();
    }

    public function calculateRevenue() : self
    {
        $this->attributes['revenue'] = $this->receiptItems()
            ->where(function ($query)
            {
                $query->orWhere('receipts.type', Invoice::class)
                    ->orWhere('receipts.type', Income::class);
            })->where('receipts.latest_status_type', '!=', Draft::class)
            ->sum('item_receipt.gross');

        return $this;
    }

    public function getGrossAttribute()
    {
        return ($this->unit_price * (1 + $this->tax));
    }

    public function getGrossInCentsAttribute()
    {
        return ($this->gross * 100);
    }

    public function getDurationHourAttribute()
    {
        return str_pad(floor($this->attributes['duration'] / 3600), 2, '0', STR_PAD_LEFT);
    }

    public function getDurationMinuteAttribute()
    {
        return str_pad(floor(($this->attributes['duration'] / 60) % 60), 2, '0', STR_PAD_LEFT);
    }

    public function getTypeNameAttribute()
    {
        return self::TYPES[$this->attributes['type']];
    }

    public function setDuration(int $hours, int $minutes) : self
    {
        if ($hours < 0)
        {
            throw new \InvalidArgumentException('hours can not be less than 0');
        }

        if ($minutes < 0 || $minutes > 59)
        {
            throw new \InvalidArgumentException('minutes have to be between 0 and 59');
        }

        $this->attributes['duration'] = ($hours * 60 * 60) + ($minutes * 60);

        return $this;
    }

    public function setUnitCostAttribute($value)
    {
        $this->attributes['unit_cost'] = number_format(str_replace(',', '.', $value), self::PRICE_DECIMALS);
    }

    public function setUnitPriceAttribute($value)
    {
        $this->attributes['unit_price'] = number_format(str_replace(',', '.', $value), self::PRICE_DECIMALS);
    }

    public function receiptItems()
    {
        return $this->hasMany('App\Receipts\Item')
            ->select('item_receipt.*')
            ->join('receipts', 'receipts.id', 'item_receipt.receipt_id')
            ->where('receipts.type', '!=', Abo::class)
            ->with('receipt');
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function receipts()
    {
        return $this->belongsToMany('App\Receipts\Receipt');
    }

    public function prices()
    {
        return $this->hasMany('App\Items\Price');
    }

    public function unit()
    {
        return $this->belongsTo('App\Unit');
    }

    public function isDeletable() : bool
    {
        if ($this->receipts()->exists())
        {
            return false;
        }

        return true;
    }

    public function scopeSearch($query, $searchtext)
    {
        if ($searchtext == '') {
            return $query;
        }

        $query->where( function ($query) use($searchtext)
        {
            $query
                ->orWhere('number', 'LIKE', '%' . $searchtext . '%')
                ->orWhere('name', 'LIKE', '%' . $searchtext . '%');
        });
    }

    public function scopeType($query, $type)
    {
        if ($type == -1) {
            return $query;
        }

        $query->where('type', $type);
    }

    public static function setup(int $companyId)
    {
        $units = Unit::withoutGlobalScope(HasCompanyScope::class)->where('company_id', $companyId)->get();

        self::create([
            'company_id' => $companyId,
            'name' => 'Beispielartikel',
            'tax' => 0.19,
            'unit_id' => $units->where('name', 'Stück')->first()->id,
            'unit_price' => 100.00,
        ]);

        self::create([
            'company_id' => $companyId,
            'name' => 'Mahngebühr',
            'tax' => 0.19,
            'unit_id' => $units->where('name', 'Euro')->first()->id,
            'unit_price' => 0,
        ]);

        self::create([
            'company_id' => $companyId,
            'name' => 'Arbeitszeit',
            'tax' => 0.19,
            'unit_id' => $units->where('name', 'Stunden')->first()->id,
            'unit_price' => 0,
            'type' => 1,
            'duration' => 3600,
        ]);
    }
}