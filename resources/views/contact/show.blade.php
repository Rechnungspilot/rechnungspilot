@extends('layouts.layout')

@section('title', 'Kontakte > ' . $contact->name)

@section('content')

    <div class="row text-right mb-3">
        <div class="col"></div>
        <div class="col-sm col-sm-auto d-flex">
            <a href="{{ url($contact->path . '/edit') }}" class="btn btn-primary"><i class="fas fa-edit"></i></a>
            <a href="{{ url('/artikel') }}" class="btn btn-secondary ml-1">Übersicht</a>
            @if ($contact->isDeletable())
                <form action="{{ url('/kontakte', $contact->id) }}" class="ml-1" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger" title="Löschen"><i class="fas fa-trash"></i></button>
                </form>
            @endif
        </div>
    </div>

    <div class="row mb-5">

        <div class="col-md-6">
            <div class="card mb-5">
                <div class="card-header">{{ $contact->name }}</div>

                <div class="card-body">
                    <div class="row">

                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-label"><b>Nummer</b></div>
                                <div class="col-value">{{ $contact->number ?:'nicht vergeben' }}</div>
                            </div>
                            @if ($contact->company_number)
                                <div class="row">
                                    <div class="col-label"><b>Meine Kundennummer bei Kontakt</b></div>
                                    <div class="col-value">{{ $contact->company_number }}</div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-label"><b>Name</b></div>
                                <div class="col-value">{{ $contact->name }}</div>
                            </div>
                            <div class="row">
                                <div class="col-label"><b>Kategorien</b></div>
                                <div class="col-value">{{ $contact->tagsString ?: 'Keine Kategorien vergeben' }}</div>
                            </div>
                            <div class="row my-3">
                                <div class="col-md-12">
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-plus-square"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="#">
                                                <form action="{{ url('/angebote') }}" method="POST">
                                                    @csrf

                                                    <input type="hidden" name="contact_id" value="{{ $contact->id }}">
                                                    <button type="submit" class="dropdown-item pointer">Angebot</button>
                                                </form>
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <form action="{{ url('/auftraege') }}" method="POST">
                                                    @csrf

                                                    <input type="hidden" name="contact_id" value="{{ $contact->id }}">
                                                    <button type="submit" class="dropdown-item pointer">Auftrag</button>
                                                </form>
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <form action="{{ url('/ausgaben') }}" method="POST">
                                                    @csrf

                                                    <input type="hidden" name="contact_id" value="{{ $contact->id }}">
                                                    <button type="submit" class="dropdown-item pointer">Ausgabe</button>
                                                </form>
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <form action="{{ url('/rechnungen') }}" method="POST">
                                                    @csrf

                                                    <input type="hidden" name="contact_id" value="{{ $contact->id }}">
                                                    <button type="submit" class="dropdown-item pointer">Rechnung</button>
                                                </form>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($contact->expense_term_id)
                                <div class="row">
                                    <div class="col-label"><b>Zahlungsbedingung Ausgabe</b></div>
                                    <div class="col-value">{{ $contact->expenseTerm->name }}</div>
                                </div>
                            @endif
                            @if ($contact->invoice_term_id)
                                <div class="row">
                                    <div class="col-label"><b>Zahlungsbedingung Rechnung</b></div>
                                    <div class="col-value">{{ $contact->invoiceTerm->name }}</div>
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-label">&nbsp;</div>
                                <div class="col-value"></div>
                            </div>
                            @if ($contact->debitor_account_number)
                                <div class="row">
                                    <div class="col-label"><b>Debitorennummer</b></div>
                                    <div class="col-value">{{ $contact->debitor_account_number }}</div>
                                </div>
                            @endif
                            @if ($contact->creditor_account_number)
                                <div class="row">
                                    <div class="col-label"><b>Kreditorennummer</b></div>
                                    <div class="col-value">{{ $contact->creditor_account_number }}</div>
                                </div>
                            @endif

                            @include('customfieldvalue.show', ['model' => $contact])

                        </div>



                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-label"><b>Telefon</b></div>
                                <div class="col-value">{{ $contact->phonenumber }}</div>
                            </div>
                            <div class="row">
                                <div class="col-label"><b>Mobil</b></div>
                                <div class="col-value">{{ $contact->mobilenumber }}</div>
                            </div>
                            <div class="row">
                                <div class="col-label"><b>Fax</b></div>
                                <div class="col-value">{{ $contact->faxnumber }}</div>
                            </div>
                            <div class="row">
                                <div class="col-label">&nbsp;</div>
                                <div class="col-value"></div>
                            </div>
                            <div class="row">
                                <div class="col-label"><b>Web</b></div>
                                <div class="col-value">{{ $contact->website }}</div>
                            </div>
                            <div class="row">
                                <div class="col-label"><b>E-Mail</b></div>
                                <div class="col-value">{{ $contact->email }}</div>
                            </div>
                            <div class="row">
                                <div class="col-label">&nbsp;</div>
                                <div class="col-value"></div>
                            </div>
                            <div class="row">
                                <div class="col-label"><b>Rechnungsadresse</b></div>
                                <div class="col-value">
                                    {{ $contact->name }}<br />
                                    {{ $contact->address }}<br />
                                    {{ $contact->postcode }} {{ $contact->city }}<br />
                                    {{ $contact->country }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-6">

            <div class="card mb-5">
                <div class="card-header">Umsatz</div>
                <div class="card-body">
                    <contact-revenue-chart :model="{{ json_encode($contact) }}"></contact-revenue-chart>
                </div>
            </div>

        </div>

    </div>

    <div class="card mb-5">
        <div class="card-header">Ansprechpartner</div>
        <div class="card-body">
            <person-table contact-id="{{ $contact->id}}"></person-table>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-header">Aufgaben</div>
        <div class="card-body">
            <appointment-table type="kontakte" :model="{{ json_encode($contact) }}" :initial-contact="{{ json_encode($contact) }}" :users="{{ json_encode($users) }}"></appointment-table>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-header">Interaktionen</div>
        <div class="card-body">
            <interaction-table :model="{{ json_encode($contact) }}"></interaction-table>
        </div>
    </div>

    @if(count($contact->receipts))
        <div class="card mb-5">
            <div class="card-header">Historie</div>
            <div class="card-body">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Datum</th>
                            <th>Vorgang</th>
                            <th>Status</th>
                            <th class="text-right">Netto</th>
                            <th class="text-right">USt.</th>
                            <th class="text-right">Brutto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contact->receipts as $receipt)
                            <?php
                                $net += $receipt->net / 100;
                                $tax_value += $receipt->tax_value;
                                $gross += $receipt->gross / 100;
                            ?>
                            <tr>
                                <td class="align-middle">{{ $receipt->date->format('d.m.Y') }}</td>
                                <td class="align-middle">
                                    <a href="{{ $receipt->path }}" target="_blank">
                                        {{ $receipt->name }}
                                    </a><br />
                                    <span class="text-muted">{{ $receipt->typeName }}</span>
                                </td>
                                <td class="align-middle">{{ $receipt->status->name }}</td>
                                <td class="align-middle text-right">{{ number_format($receipt->net, 2, ',', '.') }}</td>
                                <td class="align-middle text-right">{{ number_format($receipt->unit_price, 2, ',', '.')  }}</td>
                                <td class="align-middle text-right">{{ number_format($receipt->gross / 100, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                         <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right">{{ number_format($net, 2, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($tax_value, 2, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($gross, 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endif

    @if(count($contact->abos))
        <div class="card mb-5">
            <div class="card-header">Abos</div>
            <div class="card-body">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Datum</th>
                            <th>Abo</th>
                            <th>Status</th>
                            <th class="text-right">Netto</th>
                            <th class="text-right">USt.</th>
                            <th class="text-right">Brutto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contact->abos as $receipt)
                            <?php
                                $net += $receipt->net / 100;
                                $tax_value += $receipt->tax_value;
                                $gross += $receipt->gross / 100;
                            ?>
                            <tr>
                                <td class="align-middle">{{ $receipt->date->format('d.m.Y') }}</td>
                                <td class="align-middle">
                                    <a href="{{ $receipt->path }}" target="_blank">
                                        {{ $receipt->name }}
                                    </a>
                                </td>
                                <td class="align-middle">{{ $receipt->status->name }}</td>
                                <td class="align-middle text-right">{{ number_format($receipt->net, 2, ',', '.') }}</td>
                                <td class="align-middle text-right">{{ number_format($receipt->unit_price, 2, ',', '.')  }}</td>
                                <td class="align-middle text-right">{{ number_format($receipt->gross / 100, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                         <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right">{{ number_format($net, 2, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($tax_value, 2, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($gross, 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endif

    <div class="card mb-5">
        <div class="card-header">Dateien</div>
        <div class="card-body">
            <userfileable-table uri="/kontakte" :model="{{ json_encode($contact) }}" token="{{ csrf_token() }}"></userfileable-table>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-header">Kommentare</div>
        <div class="card-body">
            <comments uri="/kontakte" :item="{{ json_encode($contact) }}"></comments>
        </div>
    </div>

@endsection