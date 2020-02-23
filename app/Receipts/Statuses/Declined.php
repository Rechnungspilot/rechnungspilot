<?php

namespace App\Receipts\Statuses;

use App\Receipts\Statuses\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Parental\HasParent;

class Declined extends Status
{
    use HasParent;

    const NAME = 'Abgelehnt';
    const RANK = 4;

    protected $action = 'ablehnen';

    public function getNameAttribute()
    {
        return self::NAME;
    }

    public function getDescriptionAttribute()
    {
        return Arr::get($this->data, 'text', '');
    }

    public function getDataAttributesAttribute()
    {
        return [
            'text' => [
                'label' => 'Grund',
                'type' => 'textarea',
                'value' => '',
            ],
        ];
    }

    protected function associated()
    {
        $this->receipt->todos()->delete();
    }
}
