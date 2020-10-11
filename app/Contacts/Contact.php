<?php

namespace App\Contacts;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use App\Contacts\Interaction;
use App\Models\CustomFields\CustomFieldValue;
use App\Receipts\Abos\Abo;
use App\Receipts\Income;
use App\Receipts\Invoice;
use App\Receipts\Statuses\Draft;
use App\Todos\Todo;
use App\Traits\HasComments;
use App\Traits\HasCompany;
use App\Traits\HasCustomFields;
use App\Traits\HasTags;
use App\Traits\HasUserfiles;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Contact extends Model
{
    use HasComments, HasCompany, HasCustomFields, HasTags, HasUserfiles, EagerLoadPivotTrait;

    public $uri = '/kontakte';

    protected $appends = [
        'name',
        'path',
        'link',
    ];

    protected $fillable = [
        'address',
        'bankname',
        'billing_address',
        'bic',
        'city',
        'company',
        'company_id',
        'coordinates_set_at',
        'country',
        'email',
        'email_receipt',
        'euvatnumber',
        'faxnumber',
        'firstname',
        'iban',
        'invoice_term_id',
        'expense_term_id',
        'lastname',
        'lat',
        'lng',
        'mobilenumber',
        'number',
        'phonenumber',
        'postcode',
        'type',
        'vatnumber',
        'website',
        'debitor_account_number',
        'creditor_account_number',
        'company_number',
    ];

    static function nextNumber()
    {
        return self::max('number') + 1;
    }

    public function cache()
    {
        $this->calculateRevenue();

        $this->save();
    }

    public function calculateRevenue()
    {
        $this->attributes['revenue'] = $this->receipts()
            ->where(function ($query)
            {
                $query->orWhere('receipts.type', Invoice::class)
                    ->orWhere('receipts.type', Income::class);
            })
            ->where('latest_status_type', '!=', Draft::class)
            ->sum('gross');
    }

    public function getBillingAddressAttribute()
    {
        if (Arr::has($this->attributes, 'billing_address') && isset($this->attributes['billing_address'])) {
            return $this->attributes['billing_address'];
        }

        return $this->name . "\n" . $this->address . "\n" .  $this->postcode . ' ' . $this->city . ($this->country ? "\n" . $this->country : '');
    }

    public function getNameAttribute() {

        return ($this->attributes['company'] ?: $this->attributes['lastname'] . ', ' . $this->attributes['firstname']);

    }

    public function setEmailReceiptAttribute($value)
    {
        $this->attributes['email_receipt'] = $value == -1 ? null : $value;
    }

    public function getPathAttribute()
    {
        return $this->uri . '/' . $this->id;
    }

    public function getLinkAttribute() : string
    {
        return '<a href="' . $this->uri . '/' . $this->id . '">' . $this->name . '</a>';
    }

    public function defaultReceiptEmail(string $type)
    {
        return ($this->people()->where('default_' . $type, 1)->first()->email ?? '') ?: $this->email;
    }

    public function isDeletable()
    {
        return (! $this->receipts()->exists() && ! $this->people()->exists());
    }

    public function interactions() : HasMany
    {
        return $this->hasMany(Interaction::class);
    }

    public function people()
    {
        return $this->hasMany('App\Contacts\Person');
    }

    public function receipts()
    {
        return $this->hasMany('App\Receipts\Receipt')->where('type', '!=', Abo::class);
    }

    public function abos()
    {
        return $this->belongsToMany('App\Receipts\Receipt', 'contact_receipt');
    }

    public function expenseTerm()
    {
        return $this->belongsTo('App\Receipts\Term', 'expense_term_id');
    }

    public function invoiceTerm()
    {
        return $this->belongsTo('App\Receipts\Term', 'invoice_term_id');
    }

    public function todos() : BelongsToMany
    {
        return $this->belongsToMany(Todo::class);
    }

    public function scopeSearch($query, $searchtext)
    {
        if ($searchtext == '') {
            return $query;
        }

        $query->where( function ($query) use($searchtext)
        {
            $query
                ->orWhere('company', 'LIKE', '%' . $searchtext . '%')
                ->orWhere('lastname', 'LIKE', '%' . $searchtext . '%')
                ->orWhere('firstname', 'LIKE', '%' . $searchtext . '%');
        });
    }

    public static function setup(int $companyId)
    {
        self::create([
            'company' => 'Rechnungspilot.de',
            'company_id' => $companyId,
            'email' => 'hallo@rechnungspilot.de',
            'firstname' => 'Daniel',
            'lastname' => 'Sundermeier',
            'address' => 'Forststraße 31',
            'postcode' => '32423',
            'city' => 'Minden',
            'number' => '1',
        ]);
    }
}
