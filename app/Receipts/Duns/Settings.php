<?php

namespace App\Receipts\Duns;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasCompany;

    protected $fillable = [
        'action',
        'attach_invoice',
        'company_id',
        'dun_id',
        'email',
        'level_id',
        'waiting_days',
    ];

    protected $table = 'dun_settings';

    public function dun()
    {
        return $this->belongsTo('App\Receipts\Receipt', 'dun_id');
    }

    public function level()
    {
        return $this->belongsTo('App\Receipts\Duns\Level');
    }
}
