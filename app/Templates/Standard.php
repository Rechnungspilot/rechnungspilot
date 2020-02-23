<?php

namespace App\Templates;

use Illuminate\Database\Eloquent\Model;
use Parental\HasParent;

class Standard extends Template
{
    use HasParent;

    const NAME = 'Standard';

    protected $stub = 'default';
}
