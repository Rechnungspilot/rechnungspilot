@extends('layouts.layout')

@section('title', 'Einstellungen > Nummernkreise')

@section('content')

    <div class="row">

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Nummernkreise</div>
                <div class="card-body">
                    <form action="{{ url( 'einstellungen/nummernkreise' ) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="invoice_name_format">Rechnungsnummerformat</label>
                            <input type="text" class="form-control {{ ($errors->has('invoice_name_format') ? 'is-invalid' : '') }}" id="invoice_name_format" name="invoice_name_format" value="{{ old('invoice_name_format') ?? $company->invoice_name_format }}">
                            @if ($errors->has('invoice_name_format'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('invoice_name_format') }}
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="order_name_format">Auftragssnummerformat</label>
                            <input type="text" class="form-control {{ ($errors->has('order_name_format') ? 'is-invalid' : '') }}" id="order_name_format" name="order_name_format" value="{{ old('order_name_format') ?? $company->order_name_format }}">
                            @if ($errors->has('order_name_format'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('order_name_format') }}
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="quote_name_format">Angebotsnummerformat</label>
                            <input type="text" class="form-control {{ ($errors->has('quote_name_format') ? 'is-invalid' : '') }}" id="quote_name_format" name="quote_name_format" value="{{ old('quote_name_format') ?? $company->quote_name_format }}">
                            @if ($errors->has('quote_name_format'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('quote_name_format') }}
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="delivery_name_format">Lieferscheinnummerformat</label>
                            <input type="text" class="form-control {{ ($errors->has('delivery_name_format') ? 'is-invalid' : '') }}" id="delivery_name_format" name="delivery_name_format" value="{{ old('delivery_name_format') ?? $company->delivery_name_format }}">
                            @if ($errors->has('delivery_name_format'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('delivery_name_format') }}
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="abo_name_format">Abonummerformat</label>
                            <input type="text" class="form-control {{ ($errors->has('abo_name_format') ? 'is-invalid' : '') }}" id="abo_name_format" name="abo_name_format" value="{{ old('abo_name_format') ?? $company->abo_name_format }}">
                            @if ($errors->has('abo_name_format'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('abo_name_format') }}
                                </div>
                            @endif
                        </div>

                        <button class="btn btn-primary" type="submit">Speichern</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Platzhalter</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Platzhalter</th>
                                <th>Bezeichnung</th>
                                <th class="text-right">Beispiel</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#NUMMER#</td>
                                <td>Belegnummer</td>
                                <td class="text-right">7</td>
                            </tr>
                            <tr>
                                <td>#NUMMER-2#</td>
                                <td>Zweistellige Belegnummer</td>
                                <td class="text-right">07</td>
                            </tr>
                            <tr>
                                <td>#NUMMER-3#</td>
                                <td>Dreistellige Belegnummer</td>
                                <td class="text-right">007</td>
                            </tr>
                            <tr>
                                <td>#NUMMER-4#</td>
                                <td>Vierstellige Belegnummer</td>
                                <td class="text-right">0007</td>
                            </tr>
                            <tr>
                                <td>#DATUM-J#</td>
                                <td>Belegdatum Jahr zweistellig</td>
                                <td class="text-right">19</td>
                            </tr>
                            <tr>
                                <td>#DATUM-JJ#</td>
                                <td>Belegdatum Jahr vierstellig</td>
                                <td class="text-right">2019</td>
                            </tr>
                            <tr>
                                <td>#DATUM-M#</td>
                                <td>Belegdatum Monat, ohne führende Nullen</td>
                                <td class="text-right">4</td>
                            </tr>
                            <tr>
                                <td>#DATUM-MM#</td>
                                <td>Belegdatum Monat zweistellig</td>
                                <td class="text-right">04</td>
                            </tr>
                            <tr>
                                <td>#DATUM-T#</td>
                                <td>Belegdatum Tag, ohne führende Nullen</td>
                                <td class="text-right">04</td>
                            </tr>
                            <tr>
                                <td>#DATUM-TT#</td>
                                <td>Belegdatum Tag zweistellig</td>
                                <td class="text-right">04</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

@endsection