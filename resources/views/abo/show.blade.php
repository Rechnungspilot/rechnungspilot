@extends('layouts.layout')

@section('title', $abo->typeName . ' > ' . $abo->name)

@section('buttons')
    <a href="{{ $abo->edit_path }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
    @if ($abo->settings->active)
        <form action="{{ url($abo->path . '/activate') }}" method="POST">
            @csrf
            @method('DELETE')

            <button class="btn btn-danger btn-sm ml-1" title="Deaktivieren"><i class="fas fa-fw fa-pause"></i></button>
        </form>
    @else
        <form action="{{ url($abo->path . '/activate') }}" method="POST">
            @csrf

            <button class="btn btn-success btn-sm ml-1" title="Aktivieren"><i class="fas fa-fw fa-play"></i></button>
        </form>
    @endif
    <div class="dropdown ml-1">
        <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown">
            <i class="fas fa-ellipsis-h"></i> Mehr
        </button>
        <div class="dropdown-menu">
            <h6 class="dropdown-header">Anlegen</h6>
            <form action="{{ url('/abos/aus', $abo->id) }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item pointer">Duplizieren</button>
            </form>
            <h6 class="dropdown-header">Bearbeiten</h6>
            <button class="dropdown-item pointer" data-toggle="modal" data-target="#confirm-delete">Löschen</button>
        </div>
    </div>
    @if ($abo->nextMainStatus)
        <button class="btn btn-primary btn-sm pointer ml-1" data-toggle="modal" data-target="#statusModal" data-status="{{ get_class($abo->nextMainStatus) }}">{{ ucfirst($abo->nextMainStatus->action) }}</button>
    @endif
    <a href="{{ $abo->index_path }}" class="btn btn-secondary btn-sm ml-1">Übersicht</a>
    @if($abo->isDeletable())
        <form action="{{ $abo->path }}" class="ml-1" method="POST">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-fw fa-fw fa-trash"></i></button>
        </form>
    @endif
@endsection

@section('content')

    <div class="card mb-3">
        <div class="card-body">
            <div class="row">

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-label"><b>Kontakte</b></div>
                        <div class="col-value">
                            @if ($abo->contacts_count > 1)
                                <table class="table table-fixed table-hover table-striped table-sm bg-white">
                                    <tbody>
                                        @foreach($abo->contacts as $contact)
                                            <tr>
                                                <td>{{ $contact->name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @elseif ($abo->contacts_count == 1)
                                {{ $abo->contacts->first()->name }}
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-label"><b>Status</b></div>
                        <div class="col-value">{{ $abo->settings->status }}</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Sendeoptionen</b></div>
                        <div class="col-value">{{ $abo->settings->sendMailOption }}</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Frequenz</b></div>
                        <div class="col-value">{{ $abo->settings->frequency }}</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Nächste Ausführung</b></div>
                        <div class="col-value">{{ $abo->settings->next_at->format('d.m.Y') }}</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Dauer</b></div>
                        <div class="col-value">{{ $abo->settings->expires }}</div>
                    </div>
                    @if ($abo->is_partial)
                        <div class="row">
                            <div class="col-label"><b></b></div>
                            <div class="col-value">Abschlagsrechnung</div>
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