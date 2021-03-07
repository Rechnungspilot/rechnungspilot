<div class="row">
    <div class="col-label"><b>{{ $customfieldvalue->customfield->name }}</b></div>
    <div class="col-value">
        @if ($customfieldvalue->value)
            <i class="fas fa-fw fa-check"></i>
        @else
            <i class="fas fa-fw fa-times"></i>
        @endif
    </div>
</div>