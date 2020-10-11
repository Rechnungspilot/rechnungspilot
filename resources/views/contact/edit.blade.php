@extends('layouts.layout')

@section('title', 'Kontakte > ' . $contact->name)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col">

            </div>
            <div class="col-md-auto">
                <div class="row">
                    <div class="dropdown mr-1">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-ellipsis-h"></i> Mehr
                        </button>
                        <div class="dropdown-menu">
                            <h6 class="dropdown-header">Anlegen</h6>
                            <form action="{{ url('/angebote') }}" method="POST">
                                @csrf
                                <input type="hidden" name="contact_id" value="{{ $contact->id }}">
                                <button type="submit" class="dropdown-item pointer">Angebot erstellen</button>
                            </form>
                            <form action="{{ url('/rechnungen') }}" method="POST">
                                @csrf
                                <input type="hidden" name="contact_id" value="{{ $contact->id }}">
                                <button type="submit" class="dropdown-item pointer">Rechnung erstellen</button>
                            </form>
                            <h6 class="dropdown-header">Bearbeiten</h6>
                            <button class="dropdown-item pointer" data-toggle="modal" data-target="#confirm-delete">Löschen</button>
                        </div>
                    </div>
                    <a href="{{ url('/kontakte') }}" class="btn btn-secondary mr-1">Übersicht</a>
                </div>
            </form>
            </div>
        </div>


        <form action="{{ url('/kontakte', $contact->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-row">

                <div class="col">

                    <div class="form-group">
                        <label for="number">Kundennummer</label>
                        <input type="text" class="form-control {{ ($errors->has('number') ? 'is-invalid' : '') }}" id="number" name="number" value="{{ old('number') ?? $contact->number }}">
                        @if ($errors->has('number'))
                            <div class="invalid-feedback">
                                {{ $errors->first('number') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="company_number">Meine Kundennummer bei Kontakt</label>
                        <input type="text" class="form-control {{ ($errors->has('company_number') ? 'is-invalid' : '') }}" id="company_number" name="company_number" value="{{ old('company_number') ?? $contact->company_number }}">
                        @if ($errors->has('company_number'))
                            <div class="invalid-feedback">
                                {{ $errors->first('company_number') }}
                            </div>
                        @endif
                    </div>


                    <div class="form-group">
                        <label for="company">Firma</label>
                        <input type="text" class="form-control {{ ($errors->has('company') ? 'is-invalid' : '') }}" id="company" name="company" value="{{ old('company') ?? $contact->company }}">
                        @if ($errors->has('company'))
                            <div class="invalid-feedback">
                                {{ $errors->first('company') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="lastname">Nachname</label>
                        <input type="text" class="form-control {{ ($errors->has('lastname') ? 'is-invalid' : '') }}" id="lastname" name="lastname" value="{{ old('lastname') ?? $contact->lastname }}">
                        @if ($errors->has('lastname'))
                            <div class="invalid-feedback">
                                {{ $errors->first('lastname') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="firstname">Vorname</label>
                        <input type="text" class="form-control {{ ($errors->has('firstname') ? 'is-invalid' : '') }}" id="firstname" name="firstname" value="{{ old('firstname') ?? $contact->firstname }}">
                        @if ($errors->has('firstname'))
                            <div class="invalid-feedback">
                                {{ $errors->first('firstname') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="address">Straße</label>
                        <input type="text" class="form-control {{ ($errors->has('address') ? 'is-invalid' : '') }}" id="address" name="address" value="{{ old('address') ?? $contact->address }}">
                        @if ($errors->has('address'))
                            <div class="invalid-feedback">
                                {{ $errors->first('address') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="postcode">PLZ</label>
                        <input type="text" class="form-control {{ ($errors->has('postcode') ? 'is-invalid' : '') }}" id="postcode" name="postcode" value="{{ old('postcode') ?? $contact->postcode }}">
                        @if ($errors->has('postcode'))
                            <div class="invalid-feedback">
                                {{ $errors->first('postcode') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="city">Stadt</label>
                        <input type="text" class="form-control {{ ($errors->has('city') ? 'is-invalid' : '') }}" id="city" name="city" value="{{ old('city') ?? $contact->city }}">
                        @if ($errors->has('city'))
                            <div class="invalid-feedback">
                                {{ $errors->first('city') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="country">Land</label>
                        <input type="text" class="form-control {{ ($errors->has('country') ? 'is-invalid' : '') }}" id="country" name="country" value="{{ old('country') ?? $contact->country }}">
                        @if ($errors->has('country'))
                            <div class="invalid-feedback">
                                {{ $errors->first('country') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="billing_address">Abweichende Rechnungsadresse</label>
                        <textarea class="form-control {{ ($errors->has('billing_address') ? 'is-invalid' : '') }}" id="billing_address" name="billing_address" rows="5">{{ old('billing_address') ?? ($contact->has_billing_address ? $contact->billing_address : '') }}</textarea>
                        @if ($errors->has('billing_address'))
                            <div class="invalid-feedback">
                                {{ $errors->first('billing_address') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="phonenumber">Telefon</label>
                        <input type="text" class="form-control {{ ($errors->has('phonenumber') ? 'is-invalid' : '') }}" id="phonenumber" name="phonenumber" value="{{ old('phonenumber') ?? $contact->phonenumber }}">
                        @if ($errors->has('phonenumber'))
                            <div class="invalid-feedback">
                                {{ $errors->first('phonenumber') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="mobilenumber">Mobil</label>
                        <input type="text" class="form-control {{ ($errors->has('mobilenumber') ? 'is-invalid' : '') }}" id="mobilenumber" name="mobilenumber" value="{{ old('mobilenumber') ?? $contact->mobilenumber }}">
                        @if ($errors->has('mobilenumber'))
                            <div class="invalid-feedback">
                                {{ $errors->first('mobilenumber') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="faxnumber">Fax</label>
                        <input type="text" class="form-control {{ ($errors->has('faxnumber') ? 'is-invalid' : '') }}" id="faxnumber" name="faxnumber" value="{{ old('faxnumber') ?? $contact->faxnumber }}">
                        @if ($errors->has('faxnumber'))
                            <div class="invalid-feedback">
                                {{ $errors->first('faxnumber') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="email">E-Mail</label>
                        <input type="email" class="form-control {{ ($errors->has('email') ? 'is-invalid' : '') }}" id="email" name="email" value="{{ old('email') ?? $contact->email }}">
                        @if ($errors->has('email'))
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="website">Web</label>
                        <input type="text" class="form-control {{ ($errors->has('website') ? 'is-invalid' : '') }}" id="website" name="website" value="{{ old('website') ?? $contact->website }}">
                        @if ($errors->has('website'))
                            <div class="invalid-feedback">
                                {{ $errors->first('website') }}
                            </div>
                        @endif
                    </div>

                    <tag-select class="my-2" :selected="{{ json_encode($contact->tags) }}" type="kontakte" type_id="{{ $contact->id }}"></tag-select>

                </div>
                <div class="col">

                    <div class="form-group">
                        <label for="bankname">Bank</label>
                        <input type="text" class="form-control {{ ($errors->has('bankname') ? 'is-invalid' : '') }}" id="bankname" name="bankname" value="{{ old('bankname') ?? $contact->bankname }}">
                        @if ($errors->has('bankname'))
                            <div class="invalid-feedback">
                                {{ $errors->first('bankname') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="bic">BIC</label>
                        <input type="text" class="form-control {{ ($errors->has('bic') ? 'is-invalid' : '') }}" id="bic" name="bic" value="{{ old('bic') ?? $contact->bic }}">
                        @if ($errors->has('bic'))
                            <div class="invalid-feedback">
                                {{ $errors->first('bic') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="iban">IBAN</label>
                        <input type="text" class="form-control {{ ($errors->has('iban') ? 'is-invalid' : '') }}" id="iban" name="iban" value="{{ old('iban') ?? $contact->iban }}">
                        @if ($errors->has('iban'))
                            <div class="invalid-feedback">
                                {{ $errors->first('iban') }}
                            </div>
                        @endif
                    </div>

                    <fieldset class="form-group">
                        <legend class="col-form-label">Belege per Mail</legend>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="email_receipt" id="email_receipt_1" value="1" <?php echo ($contact->email_receipt == 1 ? 'checked="checked"' : '') ?>>
                            <label class="form-check-label" for="email_receipt_1">
                                Ja
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="email_receipt" id="email_receipt_1" value="0" <?php echo ($contact->email_receipt == 0 ? 'checked="checked"' : '') ?>>
                            <label class="form-check-label" for="email_receipt_1">
                                Nein
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="email_receipt" id="email_receipt_1" value="-1" <?php echo (is_null($contact->email_receipt) ? 'checked="checked"' : '') ?>>
                            <label class="form-check-label" for="email_receipt_1">
                                Unbekannt
                            </label>
                        </div>
                    </fieldset>

                    <div class="form-group">
                        <label for="invoice_term_id">Zahlungsbedingung Rechnung</label>
                        <select class="form-control {{ ($errors->has('invoice_term_id') ? 'is-invalid' : '') }}" id="invoice_term_id" name="invoice_term_id">
                            <option value="0">Standard Zahlungsbedingung</option>
                            @foreach($terms as $term)
                                <option value="{{ $term->id }}" <?php echo ($contact->invoice_term_id == $term->id ? 'selected="selected"' : ''); ?>>{{ $term->name }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('invoice_term_id'))
                            <div class="invalid-feedback">
                                {{ $errors->first('invoice_term_id') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="expense_term_id">Zahlungsbedingung Ausgabe</label>
                        <select class="form-control {{ ($errors->has('expense_term_id') ? 'is-invalid' : '') }}" id="expense_term_id" name="expense_term_id">
                            <option value="0">Standard Zahlungsbedingung</option>
                            @foreach($termsExpense as $term)
                                <option value="{{ $term->id }}" <?php echo ($contact->expense_term_id == $term->id ? 'selected="selected"' : ''); ?>>{{ $term->name }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('expense_term_id'))
                            <div class="invalid-feedback">
                                {{ $errors->first('expense_term_id') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="vatnumber">Steuernummer</label>
                        <input type="text" class="form-control {{ ($errors->has('vatnumber') ? 'is-invalid' : '') }}" id="vatnumber" name="vatnumber" value="{{ old('vatnumber') ?? $contact->vatnumber }}">
                        @if ($errors->has('vatnumber'))
                            <div class="invalid-feedback">
                                {{ $errors->first('vatnumber') }}
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="euvatnumber">USt.-IdNr.</label>
                        <input type="text" class="form-control {{ ($errors->has('euvatnumber') ? 'is-invalid' : '') }}" id="euvatnumber" name="euvatnumber" value="{{ old('euvatnumber') ?? $contact->euvatnumber }}">
                        @if ($errors->has('euvatnumber'))
                            <div class="invalid-feedback">
                                {{ $errors->first('euvatnumber') }}
                            </div>
                        @endif
                    </div>

                    @include('customfieldvalue.edit', ['model' => $contact])

                    <div class="card">
                        <div class="card-header">Buchhaltung</div>
                        <div class="card-body">

                            <div class="form-group">
                                <label for="debitor_account_number">Debitorennummer</label>
                                <input type="text" class="form-control {{ ($errors->has('debitor_account_number') ? 'is-invalid' : '') }}" id="debitor_account_number" name="debitor_account_number" value="{{ old('debitor_account_number') ?? $contact->debitor_account_number }}">
                                @if ($errors->has('debitor_account_number'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('debitor_account_number') }}
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="creditor_account_number">Kreditorennummer</label>
                                <input type="text" class="form-control {{ ($errors->has('creditor_account_number') ? 'is-invalid' : '') }}" id="creditor_account_number" name="creditor_account_number" value="{{ old('creditor_account_number') ?? $contact->creditor_account_number }}">
                                @if ($errors->has('creditor_account_number'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('creditor_account_number') }}
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>

                </div>


            </div>

            <button type="submit" class="btn btn-primary">Speichern</button>

        </form>
    </div>
    @include('customfield.create', ['model' => $contact])
@endsection