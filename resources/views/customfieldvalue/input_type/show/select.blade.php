@if ($customfieldvalue->value)
    <div class="row">
        <div class="col-md-4"><b>{{ $customfieldvalue->customfield->name }}</b></div>
        <div class="col-md-8">{{ $customfieldvalue->value }}</div>
    </div>
@endif