<?php

namespace App\Receipts;

use App\Receipts\Expense;
use App\Receipts\Invoice;
use App\Receipts\Quote;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasCompany;

    protected $appends = [
        'typeName',
    ];

    protected $fillable = [
        'company_id',
        'days',
        'default',
        'name',
        'text',
        'type',
    ];

    public static function default(string $type, int $id = 0, int $company_id = 0)
    {
        if ($id) {
            return self::findOrFail($id);
        }


        $query = self::where('type', $type)->where('default', true);

        if ($company_id) {
            $query->where('company_id', $company_id);
        }

        return $query->limit(1)->first();
    }

    public static function typesName(string $type) : string
    {
        switch($type)
        {
            case 'ausgaben': return 'Zahlungsbedingungen'; break;
            case 'rechnungen': return 'Zahlungsbedingungen'; break;
            case 'angebote': return 'Gültigkeiten'; break;
        }

        return 'Setzen!';
    }

    public static function setDefault(self $term, string $type)
    {
        self::typeFromString($type)->where('default', 1)->update([
            'default' => 0,
        ]);

        $term->update([
            'default' => 1,
        ]);
    }

    public function getTypeNameAttribute()
    {
        switch($this->type)
        {
            case Expense::class: return 'Zahlungsbedingung'; break;
            case Invoice::class: return 'Zahlungsbedingung'; break;
            case Quote::class: return 'Gültigkeit'; break;
        }

        return 'Setzen!';
    }

    public function scopeTypeFromString(Builder $query, string $type) : Builder
    {

        return $query->where('type', $this->getTypeFromString($type));
    }

    public function scopeSearch($query, $searchtext)
    {
        if ($searchtext == '') {
            return $query;
        }

        $query->where('name', 'LIKE', '%' . $searchtext . '%');
    }


    public static function getTypeFromString(string $type) : string
    {
        switch($type)
        {
            case 'ausgaben': return Expense::class; break;
            case 'rechnungen': return Invoice::class; break;
            case 'angebote': return Quote::class; break;
        }

        return 'nicht gefunden';
    }

    public static function setup($companyId)
    {
        $receiptTypes = [
            Expense::class,
            Invoice::class,
            Quote::class,
        ];

        $terms = [ 0, 7, 14, 21, 28 ];
        foreach ($receiptTypes as $receiptType) {
            foreach ($terms as $key => $days) {
                self::create([
                    'type' => $receiptType,
                    'company_id' => $companyId,
                    'name' => ($key == 0 ? 'sofort' : $days . ' Tage'),
                    'days' => $days,
                    'default' => ($key == 0),
                ]);
            }
        }
    }
}
