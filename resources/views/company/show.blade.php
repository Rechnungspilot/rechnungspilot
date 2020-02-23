@extends('layouts.layout')

@section('title', 'Firma > ' . $company->name)

@section('content')

    <div class="row text-right mb-3">
        <div class="col"></div>
        <div class="col-sm col-sm-auto">
            <a href="{{ url('/firmen') }}" class="btn btn-secondary">Übersicht</a>
        </div>
    </div>

    <div class="row mb-5">

        <div class="col-md-6">
            <div class="card mb-5">
                <div class="card-header">{{ $company->name }}</div>

                <div class="card-body">
                    <div class="row">

                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-md-4"><b>Name</b></div>
                                <div class="col-md-8">{{ $company->name }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">&nbsp;</div>
                                <div class="col-md-8"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><b>Guthaben</b></div>
                                <div class="col-md-8">{{ number_format($company->balance, 2, ',', '.') }}</div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-md-4"><b>Telefon</b></div>
                                <div class="col-md-8">{{ $company->phonenumber }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><b>Mobil</b></div>
                                <div class="col-md-8">{{ $company->mobilenumber }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><b>Fax</b></div>
                                <div class="col-md-8">{{ $company->faxnumber }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">&nbsp;</div>
                                <div class="col-md-8"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><b>E-Mail</b></div>
                                <div class="col-md-8">{{ $company->email }}</div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">&nbsp;</div>
                                <div class="col-md-8"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><b>Rechnungsadresse</b></div>
                                <div class="col-md-8">
                                    {{ $company->name }}<br />
                                    {{ $company->address }}<br />
                                    {{ $company->postcode }} {{ $company->city }}<br />
                                    {{ $company->country }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-6">

            <div class="card mb-5">
                <div class="card-header">Infos</div>
                <div class="card-body">

                </div>
            </div>

        </div>

    </div>

    <div class="card mb-5">
        <div class="card-header">Buchungen</div>
        <div class="card-body">

        </div>
    </div>

    <div class="card mb-5">
        <div class="card-header">Kommentare</div>
        <div class="card-body">
            <comments uri="/firmen" :item="{{ json_encode($company) }}"></comments>
        </div>
    </div>

@endsection