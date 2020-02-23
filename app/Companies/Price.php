<?php

namespace App\Companies;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasCompany;

    protected $fillable = [
        'company_id',
        'price',
    ];

    protected $table = 'company_price';
}
