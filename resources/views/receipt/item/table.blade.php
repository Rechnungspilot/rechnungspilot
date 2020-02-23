<h5>Positionen</h5>
@if(count($receipt->items))
    <table class="table table-striped table-hover mb-5" id="receiptitems">
        <thead>
            <tr>
                <th width="20%">Beschreibung</th>
                <th class="text-right" width="10%">Menge</th>
                <th width="10%">Einheit</th>
                <th class="text-right" width="10%">Preis</th>
                <th class="text-right" width="10%">%</th>
                <th class="text-right" width="10%">Ust.</th>
                <th class="text-right" width="10%">Betrag</th>
                <th class="text-right" width="10%"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($receipt->items as $item)
                <tr data-receiptitem-id="{{ $item->id }}">
                    <td class="align-middle pointer">
                        {{ $item->name }}
                        @if($item->description)
                            <div class="text-muted">
                                {!! nl2br(e($item->description)) !!}
                            </div>
                        @endif
                    </td>
                    <td class="align-middle text-right pointer">
                        {{ number_format($item->quantity, 2, ",", ".") }}
                    </td>
                    <td class="align-middle pointer">
                        {{ $item->unit_id ? $item->unit->name : '' }}
                    </td>
                    <td class="align-middle text-right pointer">
                        {{ $item->unitPriceFormated }} €
                    </td>
                    <td class="align-middle text-right pointer">
                        {{ number_format($item->discount * 100, 1, ",", ".") }}
                    </td>
                    <td class="align-middle text-right pointer">
                        {{ $item->tax * 100 }}%
                    </td>
                    <td class="align-middle text-right pointer">
                        {{ number_format($item->net / 100, 2, ',', '.') }} €
                    </td>
                    <td class="align-middle buttons text-right">
                        <div class="btn-group btn-group-sm">
                            <form action="/belege/{{ $receipt->id }}/artikel/{{ $item->id }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-secondary btn-sm" title="Löschen"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>Zwischensumme</td>
                <td class="text-right"></td>
                <td></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td></td>
                <td class="text-right">{{ number_format($receipt->net / 100, 2, ',', '.') }} €</td>
                <td class="text-right"></td>
            </tr>
            @foreach($receipt->tax as $tax)
                <tr>
                    <td>Ust.</td>
                    <td class="text-right"></td>
                    <td></td>
                    <td class="text-right">{{ number_format($tax['net'] / 100, 2, ',', '.') }} €</td>
                    <td></td>
                    <td class="text-right">{{ $tax['tax'] * 100 }} %</td>
                    <td class="text-right">{{ number_format($tax['value'] / 100, 2, ',', '.') }} €</td>
                    <td class="text-right"></td>
                </tr>
            @endforeach
            <tr>
                <td>Gesamt</td>
                <td class="text-right"></td>
                <td></td>
                <td class="text-right"></td>
                <td></td>
                <td class="text-right"></td>
                <td class="text-right">{{ number_format($receipt->gross / 100, 2, ',', '.') }} €</td>
                <td class="text-right"></td>
            </tr>
        </tfoot>
    </table>
@endif

@include('receipt.item.add', [ 'id' => $receipt->id, 'items' => $items])