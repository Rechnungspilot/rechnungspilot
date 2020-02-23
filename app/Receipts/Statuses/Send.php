<?php

namespace App\Receipts\Statuses;

use App\Jobs\CacheContact;
use App\Mail\ReceiptSend;
use App\Receipts\Item as ReceiptItem;
use App\Receipts\Statuses\Status;
use App\Userfile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Parental\HasParent;

class Send extends Status
{
    use HasParent;

    const NAME = 'Versendet';
    const RANK = 2;

    protected $action = 'versenden';

    public function getNameAttribute()
    {
        return self::NAME;
    }

    public function getDescriptionAttribute()
    {
        return array_key_exists('email', $this->data) ? 'An ' . $this->data['email'] . ' versendet' : '';
    }

    public function getDataAttributesAttribute()
    {
        $type = get_class($this->receipt) == Quote::class ? 'quote' : 'invoice';

        $dataAttributes = [
            'send_mail' => [
                'label' => 'E-Mail versenden?',
                'type' => 'checkbox',
                'value' => 1,
                'checked' => $this->receipt->contact->email_receipt,
            ],
            'email' => [
                'label' => 'E-Mail',
                'type' => 'text',
                'value' => $this->receipt->contact->defaultReceiptEmail($type),
            ],
            'text' => [
                'label' => 'Text',
                'type' => 'textarea',
                'value' => $this->receipt->mailBoilerplate,
            ],
        ];

        $userfiles = [];
        foreach($this->receipt->userfiles as $userfile)
        {
            $userfiles[$userfile->id] = [
                'label' => $userfile->name,
                'value' => $userfile->id,
                'checked' => false,
            ];
        }

        if (count($userfiles) > 0)
        {
            $dataAttributes['userfiles'] = [
                'label' => 'Zusätzliche Dateien',
                'type' => 'checkboxes',
                'value' => $userfiles,
            ];
        }

        return $dataAttributes;
    }

    protected function handleAttributes(array $attributes) : array
    {
        CacheContact::dispatch($this->receipt->contact);
        ReceiptItem::cacheAll($this->receipt->items);

        if (! array_key_exists('send_mail', $attributes) || ! $attributes['email'])
        {
            unset(
                $attributes['email'],
                $attributes['text']
            );
            return $attributes;
        }

        $this->sendMail($attributes);

        return $attributes;
    }

    protected function sendMail(array $attributes)
    {
        Mail::to($attributes['email'])
            // ->bcc($meine Email)
            ->send(new ReceiptSend($this->receipt, $attributes['text'], Userfile::whereIn('id', $attributes['userfiles'] ?? [])->get()));
    }
}
