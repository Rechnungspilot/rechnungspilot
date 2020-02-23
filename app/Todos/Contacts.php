<?php

namespace App\Todos;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Contacts extends Pivot
{
    use HasCompany;

    public $incrementing = true;

    protected $table = 'contact_todo';

    protected $fillable = [
        'address',
        'company_id',
        'contact_id',
        'contact_person_id',
        'todo_id',
        'user_id',
    ];

    public function contact()
    {
        return $this->belongsTo('App\Contacts\Contact');
    }

    public function person()
    {
        return $this->belongsTo('App\Contacts\Person');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function todo()
    {
        return $this->belongsTo('App\Todos\Todo');
    }
}
