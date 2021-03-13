@extends('layouts.layout')

@section('title', 'Einstellungen > Buchhaltung')

@section('content')

    <form action="{{ url( 'einstellungen/buchhaltung' ) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">

            <div class="col-12 col-lg-6">

                <div class="card mb-3">
                    <div class="card-header">Datev</div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="datev_beraternummer">Beraternummer</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm{{ ($errors->has('datev_beraternummer') ? 'is-invalid' : '') }}" id="datev_beraternummer" name="datev_beraternummer" value="{{ old('datev_beraternummer') ?? $company->datev_beraternummer }}">
                                @error('datev_beraternummer')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="datev_mandantennummer">Mandantennummer</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm{{ ($errors->has('datev_mandantennummer') ? 'is-invalid' : '') }}" id="datev_mandantennummer" name="datev_mandantennummer" value="{{ old('datev_mandantennummer') ?? $company->datev_mandantennummer }}">
                                @error('datev_mandantennummer')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="datev_mandantennummer">Sachkontenlänge</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm{{ ($errors->has('datev_sachkontenlaenge') ? 'is-invalid' : '') }}" id="datev_sachkontenlaenge" name="datev_sachkontenlaenge" value="{{ old('datev_sachkontenlaenge') ?? $company->datev_sachkontenlaenge }}">
                                @error('datev_sachkontenlaenge')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">Debitorenkonten</div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="debitor_account_number_mode">Debitorennummern</label>
                            <div class="col-sm-8">
                                <select class="form-control form-control-sm" id="debitor_account_number_mode" name="debitor_account_number_mode">
                                    <option value="0" {{ $company->debitor_account_number_mode == 0 ? 'selected="selected"' : '' }}>Sammelkonto verwenden</option>
                                    <option value="1" {{ $company->debitor_account_number_mode == 1 ? 'selected="selected"' : '' }}>Kundennummer entspricht Debitorennummer</option>
                                    <option value="2" {{ $company->debitor_account_number_mode == 2 ? 'selected="selected"' : '' }}>Debitorennummer verwenden</option>
                                </select>
                                @error('debitor_account_number_mode')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="default_debitor_account_number">Sammelkonto</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm{{ ($errors->has('default_debitor_account_number') ? 'is-invalid' : '') }}" id="default_debitor_account_number" name="default_debitor_account_number" value="{{ old('default_debitor_account_number') ?? $company->default_debitor_account_number }}">
                                @error('default_debitor_account_number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">Falls die entsprechnde Nummer nicht am Kunden gesetzt ist, wird das Sammelkonto verwendet.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">Kreditorenkonten</div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="creditor_account_number_mode">Kreditorennnummern</label>
                            <div class="col-sm-8">
                                <select class="form-control form-control-sm" id="creditor_account_number_mode" name="creditor_account_number_mode">
                                    <option value="0" {{ $company->creditor_account_number_mode == 0 ? 'selected="selected"' : '' }}>Sammelkonto verwenden</option>
                                    <option value="2" {{ $company->creditor_account_number_mode == 2 ? 'selected="selected"' : '' }}>Kreditorennummer verwenden</option>
                                </select>
                                @error('creditor_account_number_mode')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="default_creditor_account_number">Sammelkonto</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm{{ ($errors->has('default_creditor_account_number') ? 'is-invalid' : '') }}" id="default_creditor_account_number" name="default_creditor_account_number" value="{{ old('default_creditor_account_number') ?? $company->default_creditor_account_number }}">
                                @error('default_creditor_account_number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="form-text text-muted">Falls die entsprechnde Nummer nicht am Lieferanten gesetzt ist, wird das Sammelkonto verwendet.</small>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <div class="col-12 col-lg-6">

                <div class="card mb-3">
                    <div class="card-header">Erlöskonten</div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="revenue_account_number_19">Erlöse mit 19 % USt</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm{{ ($errors->has('revenue_account_number_19') ? 'is-invalid' : '') }}" id="revenue_account_number_19" name="revenue_account_number_19" value="{{ old('revenue_account_number_19') ?? $company->revenue_account_number_19 }}">
                                @error('revenue_account_number_19')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="revenue_account_number_7">Erlöse mit 7 % USt</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm{{ ($errors->has('revenue_account_number_7') ? 'is-invalid' : '') }}" id="revenue_account_number_7" name="revenue_account_number_7" value="{{ old('revenue_account_number_7') ?? $company->revenue_account_number_7 }}">
                                @error('revenue_account_number_7')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="revenue_account_number_0_inland">steuerfreie Erlöse Inland</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm{{ ($errors->has('revenue_account_number_0_inland') ? 'is-invalid' : '') }}" id="revenue_account_number_0_inland" name="revenue_account_number_0_inland" value="{{ old('revenue_account_number_0_inland') ?? $company->revenue_account_number_0_inland }}">
                                @error('revenue_account_number_0_inland')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="default_revenue_account_number">sonstige Erlöse</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm{{ ($errors->has('default_revenue_account_number') ? 'is-invalid' : '') }}" id="default_revenue_account_number" name="default_revenue_account_number" value="{{ old('default_revenue_account_number') ?? $company->default_revenue_account_number }}">
                                @error('default_revenue_account_number')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                    </div>

                </div>

                <div class="card mb-3">
                    <div class="card-header">Aufwandskonten</div>
                    <div class="card-body">

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label col-form-label-sm" for="default_expense_account_number">Sonstige Erlöse</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm{{ ($errors->has('default_expense_account_number') ? 'is-invalid' : '') }}" id="default_expense_account_number" name="default_expense_account_number" value="{{ old('default_expense_account_number') ?? $company->default_expense_account_number }}">
                                    @error('default_expense_account_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="row my-5"><div class="col"></div></div>

        <div class="fixed-bottom bg-white p-3 text-right">
            <button type="submit" class="btn btn-primary btn-sm">Speichern</button>
        </div>

        <button class="btn btn-primary" type="submit">Speichern</button>

    </form>


@endsection