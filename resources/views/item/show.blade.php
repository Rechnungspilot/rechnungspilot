@extends('layouts.layout')

@section('title', $item->label() . ' > ' . $item->name)

@section('buttons')
    <a href="{{ $item->edit_path }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
    <a href="{{ $item->index_path }}" class="btn btn-secondary btn-sm ml-1">Übersicht</a>
    @if($item->isDeletable())
        <form action="{{ $item->path }}" class="ml-1" method="POST">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-fw fa-fw fa-trash"></i></button>
        </form>
    @endif
@endsection

@section('content')

    <div class="card mb-3">
        <div class="card-header">{{ $item->name }}</div>

        <div class="card-body">
            <div class="row">

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-label"><b>Typ</b></div>
                        <div class="col-value">{{ $item->typeName }}</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Nummer</b></div>
                        <div class="col-value">{{ $item->number ?:'nicht vergeben' }}</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Name</b></div>
                        <div class="col-value">{{ $item->name }}</div>
                    </div>
                    @if ($item->type == 1)
                        <div class="row">
                            <div class="col-label"><b>Dauer</b></div>
                            <div class="col-value">{{ $item->durationHour }}:{{ $item->durationMinute }} h</div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-label"><b>Kategorien</b></div>
                        <div class="col-value">{{ $item->tagsString ?: 'Keine Kategorien vergeben' }}</div>
                    </div>
                    @if ($item->description)
                        <div class="row mt-3">
                            <div class="col-md-12">{{ $item->description }}</div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-label">&nbsp;</div>
                        <div class="col-value"></div>
                    </div>
                    @include('customfieldvalue.show', ['model' => $item])

                </div>

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-label"><b>Verkaufspreis (netto)</b></div>
                        <div class="col-value">{{ number_format($item->unit_price, $item->decimals, ',', '.') }} €</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Verkaufspreis (brutto)</b></div>
                        <div class="col-value">{{ number_format($item->gross, $item->decimals, ',', '.') }} €</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Einkaufspreis (netto)</b></div>
                        <div class="col-value">{{ number_format($item->unit_cost, $item->decimals, ',', '.') }} €</div>
                    </div>
                    <div class="row">
                        <div class="col-label">&nbsp;</div>
                        <div class="col-value"></div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>Einheit</b></div>
                        <div class="col-value">{{ $item->unit->name }} ({{ $item->unit->abbreviation }})</div>
                    </div>
                    <div class="row">
                        <div class="col-label"><b>USt.</b></div>
                        <div class="col-value">{{ $item->tax * 100 }}%</div>
                    </div>
                    <div class="row">
                        <div class="col-label">&nbsp;</div>
                        <div class="col-value"></div>
                    </div>
                    @if($item->expense_account_number)
                        <div class="row">
                            <div class="col-label"><b>Buchungskonto Ausgabe</b></div>
                            <div class="col-value">{{ $item->expense_account_number }}</div>
                        </div>
                    @endif
                    @if($item->revenue_account_number)
                        <div class="row">
                            <div class="col-label"><b>Erlöskonto</b></div>
                            <div class="col-value">{{ $item->revenue_account_number }}</div>
                        </div>
                    @endif
                    @if($item->cost_center)
                        <div class="row">
                            <div class="col-label"><b>Kostenstelle</b></div>
                            <div class="col-value">{{ $item->cost_center }}</div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Umsatz</div>
        <div class="card-body">
            <item-revenue-chart :model="{{ json_encode($item) }}"></item-revenue-chart>
        </div>
    </div>

    @if ($item->type == \App\Item::TYPE_PRODUCT)
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between">
                <div class="">Artikel</div>
                <a class="text-body" href="{{ \App\Models\Items\Article::indexPath(['item_id' => $item->id]) }}/create"><i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="card-body">
                <items-articles-table :model="{{ json_encode($item) }}" index-path="{{ \App\Models\Items\Article::indexPath(['item_id' => $item->id]) }}"></items-articles-table>
            </div>
        </div>
    @endif

    @if(count($item->receiptItems))
        <div class="card mb-3">
            <div class="card-header">Historie</div>
            <div class="card-body">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Datum</th>
                            <th>Vorgang</th>
                            <th>Kontakt</th>
                            <th class="text-right">Menge</th>
                            <th class="text-right">Stückpreis</th>
                            <th class="text-right">Gesamt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($item->receiptItems as $receiptItem)
                            <?php
                                $quantity += $receiptItem->quantity;
                                $unit_price_sum += $receiptItem->unit_price;
                                $gross += $receiptItem->gross / 100;
                            ?>
                            <tr>
                                <td class="align-middle">{{ $receiptItem->receipt->date->format('d.m.Y') }}</td>
                                <td class="align-middle">
                                    <a href="{{ $receiptItem->receipt->path }}" target="_blank">
                                        {{ $receiptItem->receipt->name }}
                                    </a><br />
                                    <span class="text-muted">{{ $receiptItem->receipt->typeName }}</span>
                                </td>
                                <td class="align-middle">
                                    <a href="{{ $receiptItem->receipt->contact->path }}" target="_blank">
                                        {{ $receiptItem->receipt->contact->name }}
                                    </a>
                                </td>
                                <td class="align-middle text-right">{{ number_format($receiptItem->quantity, $item->decimals, ',', '.') }}</td>
                                <td class="align-middle text-right">{{ number_format($receiptItem->unit_price, $item->decimals, ',', '.')  }}</td>
                                <td class="align-middle text-right">{{ number_format($receiptItem->gross / 100, $item->decimals, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    @if ($quantity > 0)
                        <tfoot>
                             <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right">{{ number_format($quantity, $item->decimals, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($unit_price_sum/$quantity, $item->decimals, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($gross, $item->decimals, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    @endif

    <div class="card mb-3">
        <div class="card-header">Kommentare</div>
        <div class="card-body">
            <comments uri="{{ $item->uri }}" :item="{{ json_encode($item) }}"></comments>
        </div>
    </div>

@endsection