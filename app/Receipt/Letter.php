<?php

namespace App\Receipt;

use App\Receipts\Receipt;
use App\Receipts\Statuses\Draft;
use App\Receipts\Statuses\MorphedTo;
use App\Receipts\Statuses\Send;
use Illuminate\Database\Eloquent\Model;
use Parental\HasParent;

class Letter extends Receipt
{
    use HasParent;

    const AVAILABLE_STATUSES = [
        Draft::class => Draft::NAME,
        Send::class => Send::NAME,
    ];

    const LABEL_SINGULAR = 'Brief';
    const LABEL_PLURAL = 'Briefe';
    const SLUG = 'briefe';
    const URI = '/' . self::SLUG;

    protected $typeName = 'Brief';
    protected $uri = self::URI;

    public $dateName = 'Datum';

    public static function from(Receipt $receipt) : self {

        $attributes = $receipt->getAttributes();
        unset(
            $attributes['type'],
            $attributes['number'],
            $attributes['date'],
            $attributes['date_due'],
            $attributes['term_id'],
            $attributes['text_below'],
            $attributes['latest_status_type'],
            $attributes['latest_status_id']
        );

        $attributes['name'] = '';
        $letter = self::create($attributes);

        $morphedToStatus = new MorphedTo();
        $morphedToStatus->data = [
            'to_id' => $letter->id,
        ];
        $receipt->setStatus($morphedToStatus);

        $draftStatus = new Draft();
        $draftStatus->data = [
            'from_id' => $receipt->id,
        ];
        $letter->setStatus($draftStatus);

        return $letter;
    }

    public function setName()
    {
        $this->attributes['name'] = $this->date->format('d.m.Y');
    }
}
