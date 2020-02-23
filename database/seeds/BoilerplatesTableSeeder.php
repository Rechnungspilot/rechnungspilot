<?php

use App\Company;
use App\Receipts\Boilerplate;
use Illuminate\Database\Seeder;

class BoilerplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Company::all() as $company) {
            Boilerplate::create([
                'company_id' => $company->id,
                'name' => 'Rechnung Einleitungstext',
                'text' => 'Vielen Dank für Ihren Auftrag. Wir berechnen Ihnen folgende Lieferung bzw. Leistung:',
                'standard' => Boilerplate::STANDARD_INVOICE_ABOVE
            ]);

            Boilerplate::create([
                'company_id' => $company->id,
                'name' => 'Rechnung Schlusstext',
                'text' => 'Wir bedanken uns für Ihren Auftrag und freuen uns auf die weitere Zusammenarbeit.',
                'standard' => Boilerplate::STANDARD_INVOICE_BELOW
            ]);

            Boilerplate::create([
                'company_id' => $company->id,
                'name' => 'Rechnung E-Mail',
                'text' => "Sehr geehrte Damen und Herren,\nim Anhang finden Sie die aktuelle Rechnung.",
                'standard' => Boilerplate::STANDARD_INVOICE_MAIL
            ]);

            Boilerplate::create([
                'company_id' => $company->id,
                'name' => 'Angebot Einleitungstext',
                'text' => 'Wir danken Ihnen für Ihre Anfrage und bieten Ihnen wie folgt an:',
                'standard' => Boilerplate::STANDARD_QUOTE_ABOVE
            ]);

            Boilerplate::create([
                'company_id' => $company->id,
                'name' => 'Angebot Schlusstext',
                'text' => 'Sagt Ihnen unser Angebot zu? Wenn Sie Fragen haben, beraten wir Sie gerne.',
                'standard' => Boilerplate::STANDARD_QUOTE_BELOW
            ]);

            Boilerplate::create([
                'company_id' => $company->id,
                'name' => 'Angebot E-Mail',
                'text' => "Sehr geehrte Damen und Herren,\nim Anhang finden Sie das aktuelle Angebot.",
                'standard' => Boilerplate::STANDARD_QUOTE_MAIL
            ]);

            Boilerplate::create([
                'company_id' => $company->id,
                'name' => 'Lieferschein Schlusstext',
                'text' => 'Die Ware bleibt bis zur vollständigen Bezahlung unser Eigentum.',
                'standard' => Boilerplate::STANDARD_DELIVERY_BELOW
            ]);

            Boilerplate::create([
                'company_id' => $company->id,
                'name' => 'Lieferschein E-Mail',
                'text' => "Sehr geehrte Damen und Herren,\nim Anhang finden Sie den aktuelle Lieferschein.",
                'standard' => Boilerplate::STANDARD_DELIVERY_MAIL
            ]);

            // Boilerplate::create([
            //     'company_id' => $company->id,
            //     'name' => 'Auftrag Einleitungstext',
            //     'text' => 'Wir danken Ihnen für den Auftrag und bestätigen ihn wie zu folgenden Konditionen:',
            //     'standard' => Boilerplate::STANDARD_ORDER_ABOVE
            // ]);

            // Boilerplate::create([
            //     'company_id' => $company->id,
            //     'name' => 'Auftrag E-Mail',
            //     'text' => "Sehr geehrte Damen und Herren,\nim Anhang finden Sie die aktuelle Auftragsbestätigung.",
            //     'standard' => Boilerplate::STANDARD_ORDER_MAIL
            // ]);

            // Boilerplate::create([
            //     'company_id' => $company->id,
            //     'name' => 'Zahlungserinnerung Einleitungstext',
            //     'text' => "Sehr geehrte Damen und Herren,\n\nin der Hektik des Alltags kann man leicht mal eine Rechnung übersehen. Deshalb schreiben wir Ihnen heute. Zu unten aufgeführten Posten ist bei uns noch keine Zahlung eingegangen.",
            //     'standard' => Boilerplate::STANDARD_DUN_1_ABOVE
            // ]);

            // Boilerplate::create([
            //     'company_id' => $company->id,
            //     'name' => 'Zahlungserinnerung Schlusstext',
            //     'text' => 'Vielleicht haben Sie die Rechnung schon bezahlt und der Betrag ist nur noch nicht auf unserem Konto verbucht. Dann ist alles in Ordnung und Sie können diesen Brief einfach wegwerfen. Sollten Sie die Rechnung tatsächlich vergessen haben, bitten wir Sie, den Betrag nun zeitnah zu überweisen. Vielen Dank!',
            //     'standard' => Boilerplate::STANDARD_DUN_1_BELOW
            // ]);

            // Boilerplate::create([
            //     'company_id' => $company->id,
            //     'name' => 'Zahlungserinnerung E-Mail',
            //     'text' => "Sehr geehrte Damen und Herren,\n\nin der Hektik des Alltags kann man leicht mal eine Rechnung übersehen. Deshalb schreiben wir Ihnen heute. Zu den in der Anlage aufgeführten Posten ist bei uns noch keine Zahlung eingegangen.\n\nWir erlauben uns daher, Sie entsprechend zu erinnern und Sie darum zu bitten, den Rechnungsbetrag zeitnah zu begleichen. Vielleicht ist die Sache aber auch schon erledigt? In dem Fall, herzlichen Dank!",
            //     'standard' => Boilerplate::STANDARD_DUN_1_MAIL
            // ]);

            // Boilerplate::create([
            //     'company_id' => $company->id,
            //     'name' => 'Zahlungserinnerung Einleitungstext',
            //     'text' => "Sehr geehrte Damen und Herren,\n\nleider konnten wir bislang noch keinen Zahlungseingang zu folgenden Posten feststellen:",
            //     'standard' => Boilerplate::STANDARD_DUN_2_ABOVE
            // ]);

            // Boilerplate::create([
            //     'company_id' => $company->id,
            //     'name' => 'Zahlungserinnerung Schlusstext',
            //     'text' => 'Wir möchten gerne Ihnen und uns unnötige Kosten und Arbeitsaufwand ersparen und bitten Sie daher, den noch offenen Betrag innerhalb von 7 Tagen zu begleichen. Vielleicht ist die Sache aber auch schon erledigt? In dem Fall, herzlichen Dank!',
            //     'standard' => Boilerplate::STANDARD_DUN_2_BELOW
            // ]);

            // Boilerplate::create([
            //     'company_id' => $company->id,
            //     'name' => 'Zahlungserinnerung E-Mail',
            //     'text' => "Sehr geehrte Damen und Herren,\n\nleider konnten wir bislang noch keinen Zahlungseingang zu den in der Anlage aufgeführten Posten feststellen.\n\nWir möchten gerne Ihnen und uns unnötige Kosten und Arbeitsaufwand ersparen und bitten Sie daher, den noch offenen Betrag innerhalb von 7 Tagen zu begleichen. Vielleicht ist die Sache aber auch schon erledigt? In dem Fall, herzlichen Dank!",
            //     'standard' => Boilerplate::STANDARD_DUN_2_MAIL
            // ]);

            // Boilerplate::create([
            //     'company_id' => $company->id,
            //     'name' => 'Zahlungserinnerung Einleitungstext',
            //     'text' => "Sehr geehrte Damen und Herren,\n\nleider konnten wir noch immer keinen Zahlungseingang zu folgenden Posten feststellen:",
            //     'standard' => Boilerplate::STANDARD_DUN_3_ABOVE
            // ]);

            // Boilerplate::create([
            //     'company_id' => $company->id,
            //     'name' => 'Zahlungserinnerung Schlusstext',
            //     'text' => 'Daher sind wir gezwungen, Sie hiermit nochmals aufzufordern, die genannte Rechnung innerhalb von spätestens 7 Tagen durch Überweisung auf unser angegebenes Konto auszugleichen. Bitte ersparen Sie Ihnen und uns unnötige Kosten, Arbeitsaufwand und Ärger, der mit dem Ausbleiben Ihrer Zahlung entstehen würde.',
            //     'standard' => Boilerplate::STANDARD_DUN_3_BELOW
            // ]);

            // Boilerplate::create([
            //     'company_id' => $company->id,
            //     'name' => 'Zahlungserinnerung E-Mail',
            //     'text' => "Sehr geehrte Damen und Herren,\n\nleider konnten wir noch immer keinen Zahlungseingang zu den in der Anlage aufgeführten Posten feststellen.\n\nDaher sind wir gezwungen, Sie hiermit nochmals aufzufordern, die genannte Rechnung innerhalb von spätestens 7 Tagen durch Überweisung auf unser angegebenes Konto auszugleichen. Bitte ersparen Sie Ihnen und uns unnötige Kosten, Arbeitsaufwand und Ärger, der mit dem Ausbleiben Ihrer Zahlung entstehen würde.",
            //     'standard' => Boilerplate::STANDARD_DUN_3_MAIL
            // ]);

            // Boilerplate::create([
            //     'company_id' => $company->id,
            //     'name' => 'Zahlungserinnerung Einleitungstext',
            //     'text' => "Sehr geehrte Damen und Herren,\n\nleider erfolgte auf unsere bisherigen Schreiben Ihrerseits keine Reaktion. Haben Sie Probleme, die noch offenen Posten zu begleichen? Lassen Sie uns darüber sprechen - wir finden sicher eine Lösung in einem klärenden Telefonat.\n\nFolgende Posten sind seit geraumer Zeit offen und bereits mehrfach angemahnt worden:",
            //     'standard' => Boilerplate::STANDARD_DUN_4_ABOVE
            // ]);

            // Boilerplate::create([
            //     'company_id' => $company->id,
            //     'name' => 'Zahlungserinnerung Schlusstext',
            //     'text' => "Wir bitten Sie hiermit letztmalig, den oben genannten Betrag innerhalb von 7 Tagen ohne Abzug zu begleichen. Sollte auch bis zu diesem Zeitpunkt keine Zahlung von Ihnen erfolgen, werden wir die Angelegenheit unserem Rechtsanwalt übergeben und ein Mahnverfahren gegen sie einleiten, das mit erheblichen Mehrkosten für Sie verbunden ist.\n\nBitte lassen Sie es nicht so weit kommen und erledigen Sie die Sache nunmehr umgehend.",
            //     'standard' => Boilerplate::STANDARD_DUN_4_BELOW
            // ]);

            // Boilerplate::create([
            //     'company_id' => $company->id,
            //     'name' => 'Zahlungserinnerung E-Mail',
            //     'text' => "Sehr geehrte Damen und Herren,\n\nleider erfolgte auf unsere bisherigen Schreiben Ihrerseits keine Reaktion. Haben Sie Probleme, die noch offenen Posten zu begleichen? Lassen Sie uns darüber sprechen - wir finden sicher eine Lösung in einem klärenden Telefonat.\n\nIn der Anlage dieser E-Mail finden Sie die noch offenen Posten, um die es geht.\n\nWir bitten Sie hiermit letztmalig, den oben genannten Betrag innerhalb von 7 Tagen ohne Abzug zu begleichen. Sollte auch bis zu diesem Zeitpunkt keine Zahlung von Ihnen erfolgen, werden wir die Angelegenheit unserem Rechtsanwalt übergeben und ein Mahnverfahren gegen sie einleiten, das mit erheblichen Mehrkosten für Sie verbunden ist.\n\nBitte lassen Sie es nicht so weit kommen und erledigen Sie die Sache nunmehr umgehend.",
            //     'standard' => Boilerplate::STANDARD_DUN_4_MAIL
            // ]);
        }
    }
}
