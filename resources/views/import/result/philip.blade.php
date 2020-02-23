<div class="alert <?php echo ($result['invoices_count'] == $result['contacts_count'] ? 'alert-success' : 'alert-warning'); ?> mt-3" role="alert">
    {{ $result['invoices_count'] }} / {{ $result['contacts_count'] }} Rechnungen erstellt.
</div>

@if(count($result['not_found_contacts']))
    <h4>Nicht zugeordnet</h4>
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>Zeile</th>
                <th>Seriennummer</th>
                <th>Name</th>
                <th class="text-right">kWh</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($result['not_found_contacts'] as $row)
                <tr>
                    <td>{{ $row['5'] }}</td>
                    <td>{{ $row['14'] }}</td>
                    <td>{{ $row['6'] }}</td>
                    <td class="text-right">{{ number_format($row['9'], 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

@if($result['invoices_count'])
    <h4>Rechnungen</h4>
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Kontakt</th>
                <th class="text-right">kWh</th>
                <th class="text-right">Brutto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($result['invoices'] as $invoice)
                <tr>
                    <td><a href="{{ $invoice->path }}" target="_blank">{{ $invoice->name }}</a></td>
                    <td><a href="{{ $invoice->contact->path }}" target="_blank">{{ $invoice->contact->name }}</a></td>
                    <td class="text-right">{{ number_format($invoice->items->first()->quantity, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format(($invoice->gross / 100), 2, ',', '.') }} €</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif