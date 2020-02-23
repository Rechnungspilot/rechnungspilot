<?php

namespace App\Contacts;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasCompany;

    protected $appends = [
        'name',
    ];

    protected $fillable = [
        'company_id',
        'contact_id',
        'default_invoice',
        'default_quote',
        'email',
        'firstname',
        'function',
        'lastname',
        'mobilenumber',
        'phonenumber',
        'title',
    ];

    public static function setDefault(self $person, string $type)
    {
        self::where('default_' . $type, 1)->update([
            'default_' . $type => 0,
        ]);

        $person->update([
            'default_' . $type => 1,
        ]);
    }

    public function contact()
    {
        return $this->belongsTo('App\Contacts\Contact');
    }

    public function getNameAttribute()
    {
        return $this->lastname . ', ' . $this->firstname;
    }

    public function scopeSearch($query, $searchtext)
    {
        if ($searchtext == '') {
            return $query;
        }

        return $query->where( function ($query) use($searchtext)
        {
            $query
                ->orWhere('title', 'LIKE', '%' . $searchtext . '%')
                ->orWhere('lastname', 'LIKE', '%' . $searchtext . '%')
                ->orWhere('firstname', 'LIKE', '%' . $searchtext . '%')
                ->orWhere('phonenumber', 'LIKE', '%' . $searchtext . '%')
                ->orWhere('mobilenumber', 'LIKE', '%' . $searchtext . '%')
                ->orWhere('email', 'LIKE', '%' . $searchtext . '%')
                ->orWhere('function', 'LIKE', '%' . $searchtext . '%');
        });
    }
}
