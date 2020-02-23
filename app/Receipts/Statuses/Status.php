<?php

namespace App\Receipts\Statuses;

use App\Receipts\Receipt;
use App\Traits\HasCompany;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
use \Parental\HasChildren;

class Status extends Model
{
    use HasCompany, HasChildren, HasJsonRelationships;

    const NAME = 'Nicht gesetzt';

    protected $action = 'aktion';

    protected $appends = [
        'name',
    ];

    protected $casts = [
        'data' => 'array',
        'date' => 'date',
    ];

    protected $fillable = [
        'company_id',
        'data',
        'date',
        'type',
    ];

    protected $table = 'receipt_statuses';

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
            if (! $model->company_id)
            {
                $model->company_id = auth()->user()->company_id;
            }
            if (! $model->date)
            {
                $model->date = date('Y-m-d');
            }
            if (! isset($model->user_id))
            {
                $model->user_id = auth()->user()->id ?? 0;
            }

            return true;
        });
    }

    public function transaction()
    {
        return $this->belongsTo('App\Transaction', 'data->transaction_id');
    }

    public function associate(Receipt $receipt, array $attributes)
    {
        $this->receipt()->associate($receipt);
        $this->setAttributes($attributes);
        $receipt->setStatus($this);
        $this->associated();
        $receipt->cache();

        return $this;
    }

    protected function setAttributes(array $attributes) : self
    {
        $this->date = is_string($attributes['date']) ? Carbon::createFromFormat('d.m.Y', $attributes['date']) : $attributes['date'];
        $this->company_id = $this->receipt->company_id;
        unset(
            $attributes['_token'],
            $attributes['date'],
            $attributes['type']
        );
        $this->data = $this->handleAttributes($attributes);

        return $this;
    }

    protected function handleAttributes(array $attributes) : array
    {
        return $attributes;
    }

    protected function associated()
    {

    }

    public function getActionAttribute()
    {
        return $this->action;
    }

    public function getNameAttribute()
    {
        return static::NAME;
    }

    public function getDataAttributesAttribute()
    {
        return [];
    }

    public function getDescriptionAttribute()
    {
        return '';
    }

    public function receipt()
    {
        return $this->belongsTo('App\Receipts\Receipt');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function cleanUp() {
        // TODO: z.B. Verbidung zwischen gelöschten Belegen aufheben
    }
}
