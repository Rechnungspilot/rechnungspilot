<?php

namespace App;

use App\Banks\Account;
use App\Contacts\Contact;
use App\Contacts\InteractionType;
use App\Item;
use App\Projects\Project;
use App\Receipts\Boilerplate;
use App\Receipts\Duns\Level;
use App\Receipts\Receipt;
use App\Receipts\Term;
use App\Templates\Template;
use App\Traits\HasComments;
use App\Unit;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasComments;

    protected $casts = [
        'charging_start_at' => 'date',
        'charging_next_at' => 'date',
    ];

    protected $fillable = [
        'abo_name_format',
        'accountholdername',
        'address',
        'balance',
        'bankname',
        'bic',
        'charging_next_at',
        'charging_start_at',
        'city',
        'coordinates_set_at',
        'creditor_account_number_mode',
        'datev_beraternummer',
        'datev_mandantennummer',
        'datev_sachkontenlaenge',
        'debitor_account_number_mode',
        'default_creditor_account_number',
        'default_debitor_account_number',
        'default_expense_account_number',
        'default_revenue_account_number',
        'delivery_name_format',
        'districtcourt',
        'email',
        'euvatnumber',
        'expense_name_format',
        'faxnumber',
        'firstname',
        'iban',
        'invoice_name_format',
        'lastname',
        'lat',
        'lng',
        'logo',
        'name',
        'order_name_format',
        'phonenumber',
        'postcode',
        'price',
        'quote_name_format',
        'revenue_account_number_0_inland',
        'revenue_account_number_19',
        'revenue_account_number_7',
        'sales_tax',
        'traderegister',
        'vatnumber',
        'web',
    ];

    public function hasDirtyAddress()
    {
        return ($this->isDirty('address') || $this->isDirty('city') || $this->isDirty('postcode'));
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = str_replace(',', '.', $value) * 100;
    }

    public function template()
    {
        return $this->hasOne('App\Templates\Template');
    }

    public function accounts()
    {
        return $this->hasMany('App\Banks\Accounts');
    }

    public function contacts() : HasMany
    {
        return $this->hasMany(Contact::class, 'company_id');
    }

    public function banks()
    {
        return $this->belongsToMany('App\Banks\Bank')
            ->using('App\Banks\Company')
            ->withPivot('id', 'username', 'pin')
            ->withTimestamps();
    }

    public function receipts() : HasMany
    {
        return $this->hasMany(Receipt::class, 'company_id');
    }

    public function invoices()
    {
        return $this->hasMany(\App\Receipts\Invoice::class, 'company_id');
    }

    public function items()
    {
        return $this->hasMany(\App\Item::class, 'company_id');
    }

    public function prices()
    {
        return $this->hasMany('App\Companies\Price');
    }

    public function transactions()
    {
        return $this->morphMany('App\Transaction', 'transactionable');
    }

    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function lock()
    {
        $this->attributes['locked'] = true;
        $this->attributes['locked_at'] = today();

        return $this;
    }

    public function unlock()
    {
        $this->attributes['locked'] = false;
        $this->attributes['locked_at'] = null;

        return $this;
    }

    public function lockAt(Carbon $lockAt)
    {
        $this->attributes['locked_at'] = $lockAt;

        return $this;
    }

    public function charge()
    {
        $this->transactions()->create([
            'account_id' => 1,
            'company_id' => $this->id,
            'amount' => $this->price,
            'type' => 'debit',
            'date' => today(),
        ]);

        if ($this->balance < $this->price)
        {
            // Zu wenig Guthaben -> E-Mail
        }

        $this->decrement('balance', $this->price);
        $this->setChargeNextAt();
    }

    protected function setChargeNextAt()
    {
        $this->charging_next_at = $this->charging_next_at->add(1, 'months');
        $this->save();
    }

    /*
     * Creates Models after registering
     */
    public function setup()
    {
        Contact::setup($this->id);
        Unit::setup($this->id);
        Item::setup($this->id);
        Level::setup($this->id);
        Term::setup($this->id);
        Account::setup($this->id);
        Boilerplate::setup($this->id);
        Template::setup($this->id);
        Project::setup($this->id);
        InteractionType::setup($this->id);
    }
}
