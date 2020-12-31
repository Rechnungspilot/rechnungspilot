@extends('layouts.layout')

@section('title', $abo->typeName . ' > ' . $abo->name)

@section('content')
    <div class="row text-right mb-3">
        <div class="col"></div>
        <div class="col-sm col-sm-auto d-flex justify-content-between">
            <a href="{{ url($abo->path . '/edit') }}" class="btn btn-primary mr-1"><i class="fas fa-edit"></i></a>
            @if ($abo->settings->active)
                <form action="{{ url($abo->path . '/aktiv') }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger" title="Deaktivieren"><i class="fas fa-fw fa-pause"></i></button>
                </form>
            @else
                <form action="{{ url($abo->path . '/aktiv') }}" method="POST">
                    @csrf

                    <button class="btn btn-success" title="Aktivieren"><i class="fas fa-fw fa-play"></i></button>
                </form>
            @endif
            <a href="{{ url('/abos/' . strtolower(class_basename($abo->settings->type))) }}" class="btn btn-secondary ml-1">Übersicht</a>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-body">
            <div class="row">

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4"><b>Kontakte</b></div>
                        <div class="col-md-8">
                            @if (count($abo->contacts))
                                <table class="table table-striped table-hover">
                                    <tbody>
                                        @foreach($abo->contacts as $contact)
                                            <tr>
                                                <td>{{ $contact->name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4"><b>Status</b></div>
                        <div class="col-md-8">{{ $abo->settings->status }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><b>Sendeoptionen</b></div>
                        <div class="col-md-8">{{ $abo->settings->sendMailOption }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><b>Frequenz</b></div>
                        <div class="col-md-8">{{ $abo->settings->frequency }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><b>Nächste Ausführung</b></div>
                        <div class="col-md-8">{{ $abo->settings->next_at->format('d.m.Y') }}</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><b>Dauer</b></div>
                        <div class="col-md-8">{{ $abo->settings->expires }}</div>
                    </div>
                    @if ($abo->is_partial)
                        <div class="row">
                            <div class="col-md-4"><b></b></div>
                            <div class="col-md-8">Abschlagsrechnung</div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-body">
            @include('receipt.status.ul', ['statuses' => $abo->statuses])
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-body">
            <comments uri="/abos" :item="{{ json_encode($abo) }}"></comments>
        </div>
    </div>

@endsection