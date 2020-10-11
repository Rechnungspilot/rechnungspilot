<?php

namespace App;

use App\Contacts\Contact;
use App\Receipts\Invoice;
use App\Receipts\Receipt;
use App\Receipts\Statuses\Draft;
use App\Receipts\Term;
use App\Todos\Todo;
use App\Traits\HasCompany;
use App\Traits\HasTags;
use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    use HasCompany, HasTags;

    protected $appends = [
        'formatedHours',
        'path',
    ];

    protected $casts = [
        'end_at' => 'datetime',
        'start_at' => 'datetime',
    ];

    protected $fillable = [
        'company_id',
        'end_at',
        'item_id',
        'note',
        'seconds',
        'start_at',
        'timeable_id',
        'timeable_type',
        'user_id',
    ];

    protected $uri = 'zeiten';

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
            $model->setSeconds();

            return true;
        });

        static::updating(function($model)
        {
            $model->setSeconds();

            return true;
        });
    }

    public static function createFromTodo(Todo $todo) : self
    {
        if ($todo->item_id == 0)
        {
            return null;
        }

        $time = self::make([
            'company_id' => $todo->company_id,
            'start_at' => $todo->start_at,
            'end_at' => $todo->end_at,
            'item_id' => $todo->item_id,
            'user_id' => $todo->user_id,
        ]);
        $time->timeable()->associate($todo);
        $time->save();

        return $time;
    }

    public function toInvoice(Invoice $invoice = null) : Invoice
    {
        $invoice = $invoice ?? $this->createInvoice();

        $item = $invoice->items()->make([
            'item_id' => $this->item->id,
            'unit_id' => $this->item->unit_id,
            'name' => $this->item->name,
            'description' => $this->item->description,
            'quantity' => $this->industryHours,
            'discount' => 0,
            'tax' => $this->item->tax,
            'unit_price' => $this->item->unit_price,
        ]);
        $item->receiptable()->associate($this);
        $item->save();

        return $invoice;
    }

    protected function createInvoice()
    {
        // TODO: Kontakt aus verknüpfter Aufgabe
        $contact = Contact::first();

        $term = Term::default(Invoice::class, $contact->invoice_term_id);

        $invoice = Invoice::create([
            'address' => $contact->billing_address,
            'company_id' => auth()->user()->company_id,
            'contact_id' => $contact->id,
            'term_id' => $term->id,
            'date_due' => now()->add($term->days, 'days'),
        ]);

        $invoice->draft([
            'time_id' => $this->id,
        ]);

        return $invoice;
    }

    public static function toIndustryHours(int $seconds)
    {
        $hours = floor($seconds / 3600);
        $mins = floor($seconds / 60 % 60);
        $secs = floor($seconds % 60);

        return round($hours + ($mins/60) + ($secs / 3600), 2);
    }

    public function getPathAttribute()
    {
        return $this->uri . '/' . $this->id;
    }

    public function getIndustryHoursAttribute()
    {
        return self::toIndustryHours($this->attributes['seconds']);
    }

    public function getFormatedHoursAttribute()
    {
        if ($this->attributes['seconds'] == 0)
        {
            return '00:00';
        }

        $hours = floor($this->attributes['seconds'] / 3600);
        $mins = floor($this->attributes['seconds'] / 60 % 60);

        return str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($mins, 2, '0', STR_PAD_LEFT);
    }

    public function setSeconds()
    {
        $this->attributes['seconds'] = is_null($this->end_at) ? 0 : $this->end_at->diffInSeconds($this->start_at);
    }

    public function timeable()
    {
        return $this->morphTo('timeable');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function receiptitems()
    {
        return $this->morphMany('App\Receipts\Item', 'receiptable');
    }

    public function item()
    {
        return $this->belongsTo('App\Item');
    }
}
