<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page
        {

        }
        body
        {
            font-family: sans-serif;
            font-size: 12px;
        }

        table
        {
            width: 100%;
        }

        table.receipts th, table.receipts td
        {
            padding: 3px;
            border-bottom: 1px solid #e5e5e5;
            vertical-align: top;
            line-height: 24px;
        }

        table.receipts
        {
            border-collapse: collapse;
        }

        table.receipts tfoot
        {
            margin-top: 10px;
        }

        .text-muted {
            color: #6c757d !important;
        }

    </style>
</head>
<body style="position: relative;">
    <!--mpdf
        <htmlpageheader name="page-header">

            @include('receipt.stubs.header.' . $template->headerPath, ['company' => $company])

            <table width="100%" autosize="1">
                <tbody>
                    <tr>
                        <td width="70%" style="vertical-align: top;" autosize="1">
                            <table width="100%" autosize="1">
                                <tr>
                                    <td style="font-size: 9px; margin-bottom: 10px;">{{ $company->name }} | {{ $company->address }} | {{ $company->postcode }} {{ $company->city }}</td>
                                </tr>
                                <tr>
                                    <td style="line-height: 1.5; margin-top: 10px;">{!! nl2br(e($receipt->address)) !!}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="30%">

                            <table width="100%" autosize="1">
                                <thead>
                                    <tr>
                                        <th colspan="2" width="80%" style="text-align: left;"><strong>{{ $receipt->typeName }} {{ $receipt->name }}</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width="50%">{{ $receipt->dateName }}</td>
                                        <td width="50%" style="text-align: right;">{{ $receipt->date->format('d.m.Y') }}</td>
                                    </tr>
                                    @if ($receipt->dateDueName)
                                        <tr>
                                            <td width="50%">{{ $receipt->dateDueName }}</td>
                                            <td width="50%" style="text-align: right;">{{ $receipt->date_due->format('d.m.Y') }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td width="50%">Kundennummer</td>
                                        <td width="50%" style="text-align: right;">{{ $receipt->contact->number }}</td>
                                    </tr>
                                    <tr>
                                        <td width="50%">Seite</td>
                                        <td width="50%" style="text-align: right;">{PAGENO} von {nb}</td>
                                    </tr>
                                </tbody>
                            </table>

                        </td>
                    </tr>
                </tbody>
            </table>
        </htmlpageheader>

        @if($template->show_footer)
            <htmlpagefooter name="page-footer">
                <table width="100%" style="border-top: 1px solid #EAEAEA; color: #646473; padding-top: 20px; font-size: 8px;">
                    <thead>
                        <tr style="font-weight: 700;">
                            <td width="35%">{{ $company->name }}</td>
                            <td width="43%">{{ $company->vatnumber ? 'Steuernummer: ' . $company->vatnumber : '' }}</td>
                            <td width="22%">Bankverbindung</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $company->address }}</td>
                            <td>{{ $company->districtcourt }}</td>
                            <td>{{ $company->bankname }}</td>
                        </tr>
                        <tr>
                            <td>{{ $company->postcode }} {{ $company->city }}</td>
                            <td>{{ $company->traderegister }}</td>
                            <td>{{ $company->iban ? 'IBAN ' . $company->iban : '' }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>{{ $company->euvatnumber ? 'USt-IdNr.: ' . $company->euvatnumber : '' }}</td>
                            <td>{{ $company->bic ? 'BIC: ' . $company->bic : ''}}</td>
                        </tr>
                    </tbody>
                </table>
            </htmlpagefooter>
            <sethtmlpagefooter name="page-footer" value="on" />
        @endif


        <sethtmlpageheader name="page-header" value="on" show-this-page="1" />
    mpdf-->

    @if ($receipt->subject)
        <p><b>{{ $receipt->subject }}</b></p>
    @endif

    @if($receipt->text_above)
        <p style="min-height: 10px;">{!! $receipt->formatBoilerplate($receipt->text_above) !!}</p>
    @endif

    @if(count($receipt->items))
        <table class="receipts" width="100%" style="" autosize="1">
            <thead style="">
                <tr style="">
                    <th width="25%" style="text-align: left;">Beschreibung</th>
                    <th width="15%" style="text-align: right;">Menge</th>
                    <th width="15%" style="text-align: left;">Einheit</th>
                    <th width="15%" style="text-align: right;">Preis</th>
                    @if($company->sales_tax)
                        <th width="15%" style="text-align: right;">USt.</th>
                    @endif
                    <th width="15%" style="text-align: right;">Betrag</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receipt->items as $item)
                    <tr style="{{ $item->description ? 'border: none;' : '' }}">
                        <td style="">{{ $item->name }}</td>
                        <td style="text-align: right;">{{ number_format($item->quantity, 2, ',', '.') }}</td>
                        <td style="">{{ $item->unit_id ? $item->unit->name : '' }}</td>
                        <td style="text-align: right;">{{ number_format($item->unit_price, $item->item->decimals, ',', '.') }} €</td>
                        @if($company->sales_tax)
                            <td style="text-align: right;">{{ number_format($item->tax * 100, 2, ',', '.') }}%</td>
                        @endif
                        <td style="text-align: right;">{{ number_format($item->net / 100, 2, ',', '.') }} €</td>
                    </tr>
                    @if($item->description)
                        <tr>
                            <td colspan="{{ $company->sales_tax ? '6' : '5' }}"><div class="text-muted">{!! nl2br(e($item->description)) !!}</div></td>
                        </tr>
                    @endif
                @endforeach

            </tbody>
            <tfoot>
                <tr style="height: 10px;">
                    <td colspan="{{ $company->sales_tax ? '6' : '5' }}"></td>
                </tr>
                @if($company->sales_tax)
                    <tr>
                        <td style="">Zwischensumme</td>
                        <td style=""></td>
                        <td style=""></td>
                        <td style=""></td>
                        <td style=""></td>
                        <td style="text-align: right; ">{{ number_format($receipt->net / 100, 2, ',', '.') }} €</td>
                    </tr>
                    @foreach($receipt->tax as $tax)
                        <tr style="">
                            <td>Ust.</td>
                            <td></td>
                            <td></td>
                            <td style="text-align: right;">{{ number_format($tax['net'] / 100, 2, ',', '.') }} €</td>
                            <td style="text-align: right;">{{ $tax['tax'] * 100 }} %</td>
                            <td style="text-align: right;">{{ number_format($tax['value'] / 100, 2, ',', '.') }} €</td>
                        </tr>
                    @endforeach
                    <tr style="">
                        <td style="">Bruttosumme</td>
                        <td style=""></td>
                        <td style=""></td>
                        <td style=""></td>
                        <td style=""></td>
                        <td style="text-align: right; ">{{ number_format($receipt->gross / 100, 2, ',', '.') }} €</td>
                    </tr>
                @else
                    <tr style="">
                        <td style="">Gesamt</td>
                        <td style=""></td>
                        <td style=""></td>
                        <td style=""></td>
                        <td style="text-align: right; ">{{ number_format($receipt->net / 100, 2, ',', '.') }} €</td>
                    </tr>
                @endif
            </tfoot>
        </table>
    @endif

    @if($receipt->text)
        <p>{!! $receipt->formatBoilerplate($receipt->text) !!}</p>
    @endif

    @if (count($receipt->partialinvoices))
        @php
            $toPaySum = $receipt->gross;
        @endphp
        <h4 style="margin-top: 50px;">Abschlagsrechnungen</h4>
        <table class="receipts" width="100%" style="" autosize="1">
            <thead>
                <tr>
                    <th width="25%" style="text-align: left;">Datum</th>
                    <th width="15%"></th>
                    <th width="15%" style="text-align: left;">#</th>
                    <th width="15%" style="text-align: right;">Gesamt</th>
                    <th width="15%" style="text-align: right;">Bezahlt</th>
                    <th width="15%" style="text-align: right;">Offen</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receipt->partialinvoices as $partial)
                    @php
                        $toPay = ($partial->gross - $partial->outstanding);
                        $toPaySum -= $toPay;
                    @endphp
                    <tr>
                        <td style="text-align: left;">{{ $partial->date->format('d.m.Y') }}</td>
                        <td style="text-align: left;"></td>
                        <td style="text-align: left;">{{ $partial->name }}</td>
                        <td style="text-align: right;">{{ number_format(($partial->gross / 100), 2, ',', '.') }} €</td>
                        <td style="text-align: right;">{{ number_format(($toPay / 100), 2, ',', '.') }} €</td>
                        <td style="text-align: right;">{{ number_format(($partial->outstanding / 100), 2, ',', '.') }} €</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" style="text-align: left;">Zu zahlen</th>
                    <th style="text-align: right;">{{ number_format(($toPaySum / 100), 2, ',', '.') }} €</th>
                </tr>
            </tfoot>
        </table>
    @endif

    @if($receipt->text_below)
        <p>{!! $receipt->formatBoilerplate($receipt->text_below) !!}</p>
    @endif

    @if($receipt->term_id && $receipt->term->text)
        <p>{!! nl2br(e($receipt->formatBoilerplate($receipt->term->text))) !!}</p>
    @endif
</body>
</html>