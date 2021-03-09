@extends('layouts.layout')

@section('title', $contact->label() . ' > ' . $contact->name)

@section('buttons')
    <a href="{{ $contact->path }}" class="btn btn-secondary ml-1">Übersicht</a>
@endsection

@section('content')

    <form action="{{ $contact->path }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">

            <div class="col-12 col-lg-6">

                <div class="card mb-3">
                    <div class="card-header">Allgemein</div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="number">Kundennummer</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('number') ? 'is-invalid' : '') }}" id="number" name="number" value="{{ old('number') ?? $contact->number }}">
                                @if ($errors->has('number'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('number') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="company_number">Meine Kundennummer bei Kontakt</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('company_number') ? 'is-invalid' : '') }}" id="company_number" name="company_number" value="{{ old('company_number') ?? $contact->company_number }}">
                                @if ($errors->has('company_number'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('company_number') }}
                                    </div>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="company">Firma</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('company') ? 'is-invalid' : '') }}" id="company" name="company" value="{{ old('company') ?? $contact->company }}">
                                @if ($errors->has('company'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('company') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="lastname">Nachname</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('lastname') ? 'is-invalid' : '') }}" id="lastname" name="lastname" value="{{ old('lastname') ?? $contact->lastname }}">
                                @if ($errors->has('lastname'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('lastname') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="firstname">Vorname</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('firstname') ? 'is-invalid' : '') }}" id="firstname" name="firstname" value="{{ old('firstname') ?? $contact->firstname }}">
                                @if ($errors->has('firstname'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('firstname') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="address">Straße</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('address') ? 'is-invalid' : '') }}" id="address" name="address" value="{{ old('address') ?? $contact->address }}">
                                @if ($errors->has('address'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('address') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="postcode">PLZ</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('postcode') ? 'is-invalid' : '') }}" id="postcode" name="postcode" value="{{ old('postcode') ?? $contact->postcode }}">
                                @if ($errors->has('postcode'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('postcode') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="city">Stadt</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('city') ? 'is-invalid' : '') }}" id="city" name="city" value="{{ old('city') ?? $contact->city }}">
                                @if ($errors->has('city'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('city') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="country">Land</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('country') ? 'is-invalid' : '') }}" id="country" name="country" value="{{ old('country') ?? $contact->country }}">
                                @if ($errors->has('country'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('country') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <tag-select class="my-2" :selected="{{ json_encode($contact->tags) }}" index-path="{{ $contact::indexPathTags() }}" path="{{ $contact->tags_path }}"></tag-select>

                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">

                <div class="card mb-3">
                    <div class="card-header">Kontakt</div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="phonenumber">Telefon</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('phonenumber') ? 'is-invalid' : '') }}" id="phonenumber" name="phonenumber" value="{{ old('phonenumber') ?? $contact->phonenumber }}">
                                @if ($errors->has('phonenumber'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('phonenumber') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="mobilenumber">Mobil</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('mobilenumber') ? 'is-invalid' : '') }}" id="mobilenumber" name="mobilenumber" value="{{ old('mobilenumber') ?? $contact->mobilenumber }}">
                                @if ($errors->has('mobilenumber'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('mobilenumber') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="faxnumber">Fax</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('faxnumber') ? 'is-invalid' : '') }}" id="faxnumber" name="faxnumber" value="{{ old('faxnumber') ?? $contact->faxnumber }}">
                                @if ($errors->has('faxnumber'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('faxnumber') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="email">E-Mail</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control form-control-sm {{ ($errors->has('email') ? 'is-invalid' : '') }}" id="email" name="email" value="{{ old('email') ?? $contact->email }}">
                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('email') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="website">Web</label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm {{ ($errors->has('website') ? 'is-invalid' : '') }}" id="website" name="website" value="{{ old('website') ?? $contact->website }}">
                                @if ($errors->has('website'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('website') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">Bankdaten</div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="bankname">Bank</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('bankname') ? 'is-invalid' : '') }}" id="bankname" name="bankname" value="{{ old('bankname') ?? $contact->bankname }}">
                                @if ($errors->has('bankname'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('bankname') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="bic">BIC</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('bic') ? 'is-invalid' : '') }}" id="bic" name="bic" value="{{ old('bic') ?? $contact->bic }}">
                                @if ($errors->has('bic'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('bic') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="iban">IBAN</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('iban') ? 'is-invalid' : '') }}" id="iban" name="iban" value="{{ old('iban') ?? $contact->iban }}">
                                @if ($errors->has('iban'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('iban') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">

                <div class="card mb-3">
                    <div class="card-header">Belege</div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="billing_address">Abweichende Rechnungsadresse</label>
                            <div class="col-sm-8">
                                <textarea class="form-control form-control-sm {{ ($errors->has('billing_address') ? 'is-invalid' : '') }}" id="billing_address" name="billing_address" rows="5">{{ old('billing_address') ?? ($contact->has_billing_address ? $contact->billing_address : '') }}</textarea>
                                @if ($errors->has('billing_address'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('billing_address') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <fieldset class="form-group">
                            <div class="row">
                                <legend class="col-sm-4 col-form-label col-form-label-sm">Belege per Mail</legend>
                                <div class="col-sm-8">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="email_receipt" id="email_receipt_1" value="1" <?php echo ($contact->email_receipt == 1 ? 'checked="checked"' : '') ?>>
                                        <label class="col-form-label col-form-label-sm" class="form-check-label" for="email_receipt_1">
                                            Ja
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="email_receipt" id="email_receipt_1" value="0" <?php echo ($contact->email_receipt == 0 ? 'checked="checked"' : '') ?>>
                                        <label class="col-form-label col-form-label-sm" class="form-check-label" for="email_receipt_1">
                                            Nein
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="email_receipt" id="email_receipt_1" value="-1" <?php echo (is_null($contact->email_receipt) ? 'checked="checked"' : '') ?>>
                                        <label class="col-form-label col-form-label-sm" class="form-check-label" for="email_receipt_1">
                                            Unbekannt
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="invoice_term_id">Zahlungsbedingung Rechnung</label>
                            <div class="col-sm-8">
                                <select class="form-control form-control-sm {{ ($errors->has('invoice_term_id') ? 'is-invalid' : '') }}" id="invoice_term_id" name="invoice_term_id">
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
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="expense_term_id">Zahlungsbedingung Ausgabe</label>
                            <div class="col-sm-8">
                                <select class="form-control form-control-sm {{ ($errors->has('expense_term_id') ? 'is-invalid' : '') }}" id="expense_term_id" name="expense_term_id">
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
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="vatnumber">Steuernummer</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('vatnumber') ? 'is-invalid' : '') }}" id="vatnumber" name="vatnumber" value="{{ old('vatnumber') ?? $contact->vatnumber }}">
                                @if ($errors->has('vatnumber'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('vatnumber') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="euvatnumber">USt.-IdNr.</label>
                            <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm {{ ($errors->has('euvatnumber') ? 'is-invalid' : '') }}" id="euvatnumber" name="euvatnumber" value="{{ old('euvatnumber') ?? $contact->euvatnumber }}">
                                @if ($errors->has('euvatnumber'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('euvatnumber') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="col-12 col-lg-6">

                <div class="card mb-3">
                    <div class="card-header">Buchhaltung</div>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="debitor_account_number">Debitorennummer</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('debitor_account_number') ? 'is-invalid' : '') }}" id="debitor_account_number" name="debitor_account_number" value="{{ old('debitor_account_number') ?? $contact->debitor_account_number }}">
                                @if ($errors->has('debitor_account_number'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('debitor_account_number') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label col-form-label-sm" for="creditor_account_number">Kreditorennummer</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control form-control-sm {{ ($errors->has('creditor_account_number') ? 'is-invalid' : '') }}" id="creditor_account_number" name="creditor_account_number" value="{{ old('creditor_account_number') ?? $contact->creditor_account_number }}">
                                @if ($errors->has('creditor_account_number'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('creditor_account_number') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                @include('customfieldvalue.edit', ['model' => $contact])

            </div>

        </div>


        <div class="row my-5"><div class="col"></div></div>

        <div class="fixed-bottom bg-white p-3 text-right">
            <button type="submit" class="btn btn-primary">Speichern</button>
        </div>

    </form>

    @include('customfield.create', ['model' => $contact])
@endsection