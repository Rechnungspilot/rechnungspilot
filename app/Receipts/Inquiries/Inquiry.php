<?php

namespace App\Receipts\Inquiries;

use App\Receipts\Receipt;
use App\Receipts\Statuses\Declined;
use App\Receipts\Statuses\Draft;
use App\Receipts\Statuses\InProgress;
use App\Receipts\Statuses\MorphedTo;
use App\Receipts\Statuses\Received;
use Illuminate\Database\Eloquent\Model;
use Parental\HasParent;

class Inquiry extends Receipt
{
    use HasParent;

    const AVAILABLE_STATUSES = [
        Draft::class => Draft::NAME,
        Received::class => Received::NAME,
        InProgress::class => InProgress::NAME,
        Declined::class => Declined::NAME,
        MorphedTo::class => MorphedTo::NAME,
    ];

    const LABEL_SINGULAR = 'Anfrage';
    const LABEL_PLURAL = 'Anfragen';
    const SLUG = 'anfragen';
    const URI = '/' . self::SLUG;

    protected $typeName = self::LABEL_SINGULAR;
    protected $uri = self::URI;

    public function setName()
    {

    }

    public function isQuoteable() : bool
    {
        return $this->latest_status_type == InProgress::class;
    }

    public function getNextMainStatusAttribute()
    {
        if ($this->nextMainStatus)
        {
            return $this->nextMainStatus;
        }

        switch ($this->latest_status_type) {
            case Draft::class: $this->nextMainStatus = new Received(); break;
            case Received::class: $this->nextMainStatus = new InProgress(); break;
        }

        return $this->nextMainStatus;
    }
}
