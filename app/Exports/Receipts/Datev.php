<?php

namespace App\Exports\Receipts;

use App\Company;
use App\Receipts\Expense;
use App\Receipts\Invoice;
use App\Receipts\Item;
use App\Receipts\Receipt;
use App\Support\Csv;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Datev
{
    public static function invoices(Company $company, Collection $receipts) : string
    {
        $path = 'EXTF_Buchungsstapel.csv';

        $export_start = $receipts->min('date')->startOfMonth();
        $export_end = $receipts->max('date')->endOfMonth();

        $csv = new Csv();
        $csv->row([
            'EXTF',
            '510',
            '21',
            'Buchungsstapel',
            1,
            date('Ymdhmsv'),
            '',
            'SV',
            '',
            '',
            $company->datev_beraternummer,
            $company->datev_mandantennummer,
            $export_start->format('Y0101'),
            $company->datev_sachkontenlaenge,
            $export_start->format('Ymd'),
            $export_end->format('Ymd'),
            'Rechnungspilot',
            '',
            1,
            0,
            0,
            'EUR',
            '',
            '',
            '',
        ]);
        $csv->row([
            'Umsatz (ohne Soll/Haben-Kz)',
            'Soll/Haben-Kennzeichen',
            'WKZ Umsatz',
            'Kurs',
            'Basis-Umsatz',
            'WKZ Basis-Umsatz',
            'Konto',
            'Gegenkonto (ohne BU-Schlüssel)',
            'BU-Schlüssel',
            'Belegdatum',
            'Belegfeld 1',
            'Belegfeld 2',
            'Skonto',
            'Buchungstext',
            'Postensperre',
            'Diverse Adressnummer',
            'Geschäftspartnerbank',
            'Sachverhalt',
            'Zinssperre',
            'Beleglink',
            'Beleginfo - Art 1',
            'Beleginfo - Inhalt 1',
            'Beleginfo - Art 2',
            'Beleginfo - Inhalt 2',
            'Beleginfo - Art 3',
            'Beleginfo - Inhalt 3',
            'Beleginfo - Art 4',
            'Beleginfo - Inhalt 4',
            'Beleginfo - Art 5',
            'Beleginfo - Inhalt 5',
            'Beleginfo - Art 6',
            'Beleginfo - Inhalt 6',
            'Beleginfo - Art 7',
            'Beleginfo - Inhalt 7',
            'Beleginfo - Art 8',
            'Beleginfo - Inhalt 8',
            'KOST1 - Kostenstelle',
            'KOST2 - Kostenstelle',
            'Kost-Menge',
            'EU-Land u. UStID',
            'EU-Steuersatz',
            'Abw. Versteuerungsart',
            'Sachverhalt L+L',
            'Funktionsergänzung L+L',
            'BU 49 Hauptfunktionstyp',
            'BU 49 Hauptfunktionsnummer',
            'BU 49 Funktionsergänzung',
            'Zusatzinformation - Art 1',
            'Zusatzinformation- Inhalt 1',
            'Zusatzinformation - Art 2',
            'Zusatzinformation- Inhalt 2',
            'Zusatzinformation - Art 3',
            'Zusatzinformation- Inhalt 3',
            'Zusatzinformation - Art 4',
            'Zusatzinformation- Inhalt 4',
            'Zusatzinformation - Art 5',
            'Zusatzinformation- Inhalt 5',
            'Zusatzinformation - Art 6',
            'Zusatzinformation- Inhalt 6',
            'Zusatzinformation - Art 7',
            'Zusatzinformation- Inhalt 7',
            'Zusatzinformation - Art 8',
            'Zusatzinformation- Inhalt 8',
            'Zusatzinformation - Art 9',
            'Zusatzinformation- Inhalt 9',
            'Zusatzinformation - Art 10',
            'Zusatzinformation- Inhalt 10',
            'Zusatzinformation - Art 11',
            'Zusatzinformation- Inhalt 11',
            'Zusatzinformation - Art 12',
            'Zusatzinformation- Inhalt 12',
            'Zusatzinformation - Art 13',
            'Zusatzinformation- Inhalt 13',
            'Zusatzinformation - Art 14',
            'Zusatzinformation- Inhalt 14',
            'Zusatzinformation - Art 15',
            'Zusatzinformation- Inhalt 15',
            'Zusatzinformation - Art 16',
            'Zusatzinformation- Inhalt 16',
            'Zusatzinformation - Art 17',
            'Zusatzinformation- Inhalt 17',
            'Zusatzinformation - Art 18',
            'Zusatzinformation- Inhalt 18',
            'Zusatzinformation - Art 19',
            'Zusatzinformation- Inhalt 19',
            'Zusatzinformation - Art 20',
            'Zusatzinformation- Inhalt 20',
            'Stück',
            'Gewicht',
            'Zahlweise',
            'Forderungsart',
            'Veranlagungsjahr',
            'Zugeordnete Fälligkeit',
            'Skontotyp',
            'Auftragsnummer',
            'Buchungstyp',
            'Ust-Schlüssel (Anzahlungen)',
            'EU-Land (Anzahlungen)',
            'Sachverhalt L+L (Anzahlungen)',
            'EU-Steuersatz (Anzahlungen)',
            'Erlöskonto (Anzahlungen)',
            'Herkunft-Kz',
            'Leerfeld',
            'KOST-Datum',
            'Mandatsreferenz',
            'Skontosperre',
            'Gesellschaftername',
            'Beteiligtennummer',
            'Identifikationsnummer',
            'Zeichnernummer',
            'Postensperre bis',
            'Bezeichnung SoBil-Sachverhalt',
            'Kennzeichen SoBil-Buchung',
            'Festschreibung',
            'Leistungsdatum',
            'Datum Zuord.Steuerperiode',
        ]);

        foreach ($receipts as $key => $receipt) {
            foreach ($receipt->items as $item) {
                $csv->row([
                    number_format(abs($item->gross / 100), 2, ',', ''),
                    self::sollHaben($receipt, $item),
                    '',
                    '',
                    '',
                    '',
                    $receipt->contact->number,
                    self::accountNumber($receipt, $item),
                    $item->datev_tax_code, // Steuerschluessel
                    $receipt->date->format('dm'),
                    $receipt->name,
                    $receipt->date_due->format('dmy'),
                    '',
                    substr($item->description, 0, 60),
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    $item->item->cost_center,
                    '', // Kostenstelle 2
                    number_format($item->quantity, 2, ',', ''),
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '', // Stückmenge füllen
                    '', // Stückmenge füllen
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '', // Abschlag oder Schluss
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '0', // Festschreiben
                    $receipt->date->format('dmY'), // Leistungsdatum
                ]);
            }
        }

        $csv->save(Storage::disk('public')->path($path));

        return $path;
    }

    protected static function sollHaben(Receipt $receipt, Item $item) : string
    {
        $class_name = get_class($receipt);
        switch($class_name) {
            case Invoice::class: return ($item->gross < 0 ? 'H' : 'S'); break;
            case Expense::class: return ($item->gross < 0 ? 'S' : 'H'); break;
            default: return ''; break;
        }
    }

    protected static function accountNumber(Receipt $receipt, Item $item) : int
    {
        $class_name = get_class($receipt);
        switch($class_name) {
            case Invoice::class: return $item->item->revenue_account_number; break;
            case Expense::class: return $item->item->expense_account_number; break;
            default: return 0; break;
        }
    }
}