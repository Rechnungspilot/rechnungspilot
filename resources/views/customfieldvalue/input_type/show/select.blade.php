@if ($customfieldvalue->value)
    <div class="row">
        <div class="col-label"><b>{{ $customfieldvalue->customfield->name }}</b></div>
        <div class="col-value">{{ $customfieldvalue->value }}</div>
    </div>
@endif