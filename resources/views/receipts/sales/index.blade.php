@extends('layouts.layout')

@section('title', 'Bestellungen')

@section('buttons')

    <a href="{{ route('receipts.sales.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus-square"></i></a>

@endsection

@section('content')

    @if (count($presales))
        <table class="table table-fixed table-hover table-striped table-sm bg-white">
            <thead>
                <tr>
                    <th class="align-middle">Artikel</th>
                    <th class="align-middle text-right">Vorbestellungen</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($presales as $presale)
                    <td class="align-middle">{{ $presale->item->name }}</td>
                    <td class="align-middle text-right">{{ $presale->presale_count }}</td>
                @endforeach
            </tbody>
        </table>
    @endif

    <receipt-sales-table
        index-path="{{ route('receipts.sales.index') }}"
    ></receipt-sales-table>

@endsection