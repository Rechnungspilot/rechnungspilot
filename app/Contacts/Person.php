<?php

namespace App\Contacts;

use App\Traits\HasCompany;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasCompany,
        HasLabels,
        HasModelPath;

    const ROUTE_NAME = 'contacts.people';

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

    protected static function labels() : array
    {
        return [
            'nominativ' => [
                'singular' => 'Ansprechpartner',
                'plural' => 'Ansprechpartner',
            ],
        ];
    }

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

    public function getRouteParameterAttribute() : array
    {
        return [
            'contact' => $this->contact_id,
            'person' => $this->id,
        ];
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
