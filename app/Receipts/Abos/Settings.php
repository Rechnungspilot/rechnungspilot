<?php

namespace App\Receipts\Abos;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasCompany;

    const INTERVAL_UNITS = [
        'days' => 'Tage',
        'weeks' => 'Wochen',
        'months' => 'Monate',
        'years' => 'Jahre',
    ];

    const SEND_MAIL_OPTIONS = [
        0 => 'Entwurf erstellen, manuell versenden',
        // 1 => 'Entwurf erstellen, automatisch versenden',
    ];

    protected $casts = [
        'start_at' => 'date',
        'next_at' => 'date',
        'last_at' => 'date',
    ];

    protected $fillable = [
        'abo_id',
        'active',
        'company_id',
        'email',
        'interval_unit',
        'interval_value',
        'last_at',
        'last_count',
        'last_type',
        'next_at',
        'send_mail',
        'start_at',
    ];

    protected $table = 'abo_settings';

    public function abo()
    {
        return $this->belongsTo('App\Receipts\Receipt', 'abo_id');
    }

    public function getSendMailOptionAttribute()
    {
        return self::SEND_MAIL_OPTIONS[$this->attributes['send_mail']];
    }

    public function getStatusAttribute()
    {
        return $this->attributes['active'] ? 'Aktiv' : 'Inaktiv';
    }

    public function getFrequencyAttribute()
    {
        return $this->attributes['interval_value'] . ' ' . self::INTERVAL_UNITS[$this->attributes['interval_unit']];
    }

    public function getExpiresAttribute()
    {
        switch($this->attributes['last_type'])
        {
            case 0: return 'Unbegrenzt'; break;
            case 1: return 'Noch ' . $this->attributes['last_count'] . ' Ausführungen'; break;
            case 2: return 'Bis ' . $this->last_at->format('d.m.Y'); break;
        }

        return '';
    }

    public function setNextAt()
    {
        if (! is_null($this->last_at) && $this->last_at <= $this->next_at)
        {
            $this->update([
                'last_count' => 0,
                'active' => false,
            ]);
            return false;
        }

        return $this->update([
            'last_count' => $this->last_count ? ($this->last_count - 1) : 0,
            'next_at' => $this->next_at->add($this->interval_value, $this->interval_unit),
        ]);
    }
}
