@extends('layouts.layout')

@section('title', 'Einstellungen > Buchhaltung')

@section('content')

    <form action="{{ url( 'einstellungen/buchhaltung' ) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card mb-3">
            <div class="card-header">Debitorenkonten</div>
            <div class="card-body">
                <div class="form-group">
                    <label for="debitor_account_number_mode">Debitorennummern</label>
                    <select class="form-control" id="debitor_account_number_mode" name="debitor_account_number_mode">
                        <option value="0" {{ $company->debitor_account_number_mode == 0 ? 'selected="selected"' : '' }}>Sammelkonto verwenden</option>
                        <option value="1" {{ $company->debitor_account_number_mode == 1 ? 'selected="selected"' : '' }}>Kundennummer entspricht Debitorennummer</option>
                        <option value="2" {{ $company->debitor_account_number_mode == 2 ? 'selected="selected"' : '' }}>Debitorennummer verwenden</option>
                    </select>
                    @if ($errors->has('debitor_account_number_mode'))
                        <div class="invalid-feedback">
                            {{ $errors->first('debitor_account_number_mode') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="default_debitor_account_number">Sammelkonto</label>
                    <input type="text" class="form-control {{ ($errors->has('default_debitor_account_number') ? 'is-invalid' : '') }}" id="default_debitor_account_number" name="default_debitor_account_number" value="{{ old('default_debitor_account_number') ?? $company->default_debitor_account_number }}">
                    @if ($errors->has('default_debitor_account_number'))
                        <div class="invalid-feedback">
                            {{ $errors->first('default_debitor_account_number') }}
                        </div>
                    @endif
                    <small class="form-text text-muted">Falls die entsprechnde Nummer nicht am Kunden gesetzt ist, wird das Sammelkonto verwendet.</small>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">Kreditorenkonten</div>
            <div class="card-body">
                <div class="form-group">
                    <label for="creditor_account_number_mode">Kreditorennnummern</label>
                    <select class="form-control" id="creditor_account_number_mode" name="creditor_account_number_mode">
                        <option value="0" {{ $company->creditor_account_number_mode == 0 ? 'selected="selected"' : '' }}>Sammelkonto verwenden</option>
                        <option value="2" {{ $company->creditor_account_number_mode == 2 ? 'selected="selected"' : '' }}>Kreditorennummer verwenden</option>
                    </select>
                    @if ($errors->has('creditor_account_number_mode'))
                        <div class="invalid-feedback">
                            {{ $errors->first('creditor_account_number_mode') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="default_creditor_account_number">Sammelkonto</label>
                    <input type="text" class="form-control {{ ($errors->has('default_creditor_account_number') ? 'is-invalid' : '') }}" id="default_creditor_account_number" name="default_creditor_account_number" value="{{ old('default_creditor_account_number') ?? $company->default_creditor_account_number }}">
                    @if ($errors->has('default_creditor_account_number'))
                        <div class="invalid-feedback">
                            {{ $errors->first('default_creditor_account_number') }}
                        </div>
                    @endif
                    <small class="form-text text-muted">Falls die entsprechnde Nummer nicht am Lieferanten gesetzt ist, wird das Sammelkonto verwendet.</small>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">Erlöskonten</div>
            <div class="card-body">

                <div class="form-row">

                    <div class="col">
                        <div class="form-group">
                            <label for="revenue_account_number_19">Erlöse mit 19 % USt</label>
                            <input type="text" class="form-control {{ ($errors->has('revenue_account_number_19') ? 'is-invalid' : '') }}" id="revenue_account_number_19" name="revenue_account_number_19" value="{{ old('revenue_account_number_19') ?? $company->revenue_account_number_19 }}">
                            @if ($errors->has('revenue_account_number_19'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('revenue_account_number_19') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label for="revenue_account_number_7">Erlöse mit 7 % USt</label>
                            <input type="text" class="form-control {{ ($errors->has('revenue_account_number_7') ? 'is-invalid' : '') }}" id="revenue_account_number_7" name="revenue_account_number_7" value="{{ old('revenue_account_number_7') ?? $company->revenue_account_number_7 }}">
                            @if ($errors->has('revenue_account_number_7'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('revenue_account_number_7') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label for="revenue_account_number_0_inland">steuerfreie Erlöse Inland</label>
                            <input type="text" class="form-control {{ ($errors->has('revenue_account_number_0_inland') ? 'is-invalid' : '') }}" id="revenue_account_number_0_inland" name="revenue_account_number_0_inland" value="{{ old('revenue_account_number_0_inland') ?? $company->revenue_account_number_0_inland }}">
                            @if ($errors->has('revenue_account_number_0_inland'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('revenue_account_number_0_inland') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col">
                        <div class="form-group">
                            <label for="default_revenue_account_number">sonstige Erlöse</label>
                            <input type="text" class="form-control {{ ($errors->has('default_revenue_account_number') ? 'is-invalid' : '') }}" id="default_revenue_account_number" name="default_revenue_account_number" value="{{ old('default_revenue_account_number') ?? $company->default_revenue_account_number }}">
                            @if ($errors->has('default_revenue_account_number'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('default_revenue_account_number') }}
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">Aufwandskonten</div>
            <div class="card-body">

                    <div class="form-group">
                        <label for="default_expense_account_number">Sonstige Erlöse</label>
                        <input type="text" class="form-control {{ ($errors->has('default_expense_account_number') ? 'is-invalid' : '') }}" id="default_expense_account_number" name="default_expense_account_number" value="{{ old('default_expense_account_number') ?? $company->default_expense_account_number }}">
                        @if ($errors->has('default_expense_account_number'))
                            <div class="invalid-feedback">
                                {{ $errors->first('default_expense_account_number') }}
                            </div>
                        @endif
                    </div>

            </div>
        </div>

        <button class="btn btn-primary" type="submit">Speichern</button>

    </form>


@endsection