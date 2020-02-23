<?php

namespace App\Receipts;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;

class Boilerplate extends Model
{
    use HasCompany;

    const STANDARD_INVOICE_ABOVE = 10;
    const STANDARD_INVOICE_BELOW = 11;
    const STANDARD_INVOICE_MAIL = 12;

    const STANDARD_QUOTE_ABOVE = 20;
    const STANDARD_QUOTE_BELOW = 21;
    const STANDARD_QUOTE_MAIL = 22;

    const STANDARD_ORDER_ABOVE = 30;
    const STANDARD_ORDER_BELOW = 31;
    const STANDARD_ORDER_MAIL = 32;

    const STANDARD_DELIVERY_ABOVE = 40;
    const STANDARD_DELIVERY_BELOW = 41;
    const STANDARD_DELIVERY_MAIL = 42;

    const STANDARD_DUN_1_ABOVE = 901;
    const STANDARD_DUN_1_BELOW = 911;
    const STANDARD_DUN_1_MAIL = 922;
    const STANDARD_DUN_2_ABOVE = 902;
    const STANDARD_DUN_2_BELOW = 912;
    const STANDARD_DUN_2_MAIL = 922;
    const STANDARD_DUN_3_ABOVE = 903;
    const STANDARD_DUN_3_BELOW = 913;
    const STANDARD_DUN_3_MAIL = 923;
    const STANDARD_DUN_4_ABOVE = 904;
    const STANDARD_DUN_4_BELOW = 914;
    const STANDARD_DUN_4_MAIL = 924;

    const STANDARDS = [
        0 => 'Kein Standard',
        self::STANDARD_INVOICE_ABOVE => 'Standard über Rechnungspositionen',
        self::STANDARD_INVOICE_BELOW => 'Standard unter Rechnungspositionen',
        self::STANDARD_INVOICE_MAIL => 'Standard für Rechnungsmail',
        self::STANDARD_QUOTE_ABOVE => 'Standard über Angebotspositionen',
        self::STANDARD_QUOTE_BELOW => 'Standard unter Angebotspositionen',
        self::STANDARD_QUOTE_MAIL => 'Standard für Angebotsmail',
        self::STANDARD_DELIVERY_ABOVE => 'Standard über Lieferscheinpositionen',
        self::STANDARD_DELIVERY_BELOW => 'Standard unter Lieferscheinpositionen',
        self::STANDARD_DELIVERY_MAIL => 'Standard für Lieferscheinmail',
    ];

    const PLACEHOLDER = [
        '#DATUM#' => 'Datum',
        '#KONTAKT#' => 'Kontakt',
    ];

    protected $appends = [
        'standardName',
    ];

    protected $fillable = [
        'company_id',
        'name',
        'standard',
        'sort',
        'text',
    ];

    public static function default(int $key) : string
    {
        $texts = [];
        $boilerplates = self::where('standard', $key)->get();
        foreach ($boilerplates as $key => $boilerplate) {
            $texts[] = $boilerplate->text;
        }

        return join($texts, "\n\n");
    }

    public function getStandardNameAttribute()
    {
        return self::STANDARDS[$this->standard];
    }

    public static function setup(int $companyId)
    {
        self::create([
            'company_id' => $companyId,
            'name' => 'Rechnung Einleitungstext',
            'text' => 'Vielen Dank für Ihren Auftrag. Wir berechnen Ihnen folgende Lieferung bzw. Leistung:',
            'standard' => self::STANDARD_INVOICE_ABOVE
        ]);

        self::create([
            'company_id' => $companyId,
            'name' => 'Rechnung Schlusstext',
            'text' => 'Wir bedanken uns für Ihren Auftrag und freuen uns auf die weitere Zusammenarbeit.',
            'standard' => self::STANDARD_INVOICE_BELOW
        ]);

        self::create([
            'company_id' => $companyId,
            'name' => 'Rechnung E-Mail',
            'text' => "Sehr geehrte Damen und Herren,\nim Anhang finden Sie die aktuelle Rechnung.",
            'standard' => self::STANDARD_INVOICE_MAIL
        ]);

        self::create([
            'company_id' => $companyId,
            'name' => 'Angebot Einleitungstext',
            'text' => 'Wir danken Ihnen für Ihre Anfrage und bieten Ihnen wie folgt an:',
            'standard' => self::STANDARD_QUOTE_ABOVE
        ]);

        self::create([
            'company_id' => $companyId,
            'name' => 'Angebot Schlusstext',
            'text' => 'Sagt Ihnen unser Angebot zu? Wenn Sie Fragen haben, beraten wir Sie gerne.',
            'standard' => self::STANDARD_QUOTE_BELOW
        ]);

        self::create([
            'company_id' => $companyId,
            'name' => 'Angebot E-Mail',
            'text' => "Sehr geehrte Damen und Herren,\nim Anhang finden Sie das aktuelle Angebot.",
            'standard' => self::STANDARD_QUOTE_MAIL
        ]);

        self::create([
            'company_id' => $companyId,
            'name' => 'Lieferschein Schlusstext',
            'text' => 'Die Ware bleibt bis zur vollständigen Bezahlung unser Eigentum.',
            'standard' => self::STANDARD_DELIVERY_BELOW
        ]);

        self::create([
            'company_id' => $companyId,
            'name' => 'Lieferschein E-Mail',
            'text' => "Sehr geehrte Damen und Herren,\nim Anhang finden Sie den aktuelle Lieferschein.",
            'standard' => self::STANDARD_DELIVERY_MAIL
        ]);

    }
}
