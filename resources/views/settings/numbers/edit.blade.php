@extends('layouts.layout')

@section('title', 'Einstellungen > Nummernkreise')

@section('content')

    <form action="{{ url( 'einstellungen/nummernkreise' ) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">

            <div class="col-12 col-lg-6">

                <div class="card">
                    <div class="card-header">Nummernkreise</div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="invoice_name_format">Rechnungsnummerformat</label>
                            <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm @error('invoice_name_format') is-invalid @enderror" id="invoice_name_format" name="invoice_name_format" value="{{ old('invoice_name_format') ?? $company->invoice_name_format }}">
                                    @error('invoice_name_format')
                                        <div class="invalid-feedback">
                                            {{ $errors->first('invoice_name_format') }}
                                        </div>
                                    @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="order_name_format">Auftragssnummerformat</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('order_name_format') is-invalid @enderror" id="order_name_format" name="order_name_format" value="{{ old('order_name_format') ?? $company->order_name_format }}">
                                @error('order_name_format')
                                    <div class="invalid-feedback">
                                        {{ $errors->first('order_name_format') }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="quote_name_format">Angebotsnummerformat</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('quote_name_format') is-invalid @enderror" id="quote_name_format" name="quote_name_format" value="{{ old('quote_name_format') ?? $company->quote_name_format }}">
                                @error('quote_name_format')
                                    <div class="invalid-feedback">
                                        {{ $errors->first('quote_name_format') }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="delivery_name_format">Lieferscheinnummerformat</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('delivery_name_format') is-invalid @enderror" id="delivery_name_format" name="delivery_name_format" value="{{ old('delivery_name_format') ?? $company->delivery_name_format }}">
                                @error('delivery_name_format')
                                    <div class="invalid-feedback">
                                        {{ $errors->first('delivery_name_format') }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="abo_name_format">Abonummerformat</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm @error('abo_name_format') is-invalid @enderror" id="abo_name_format" name="abo_name_format" value="{{ old('abo_name_format') ?? $company->abo_name_format }}">
                                @error('abo_name_format')
                                    <div class="invalid-feedback">
                                        {{ $errors->first('abo_name_format') }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-header">Platzhalter</div>
                <div class="card-body">

                    <table class="table table-fixed table-hover table-striped table-sm bg-white">
                        <thead>
                            <tr>
                                <th width="125">Platzhalter</th>
                                <th>Bezeichnung</th>
                                <th class="text-right" width="100">Beispiel</th>
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

    <div class="row my-5"><div class="col"></div></div>

    <div class="fixed-bottom bg-white p-3 text-right">
        <button type="submit" class="btn btn-primary btn-sm">Speichern</button>
    </div>

    </form>

@endsection