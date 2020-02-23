<div class="row">
    <div class="col-md-4"><b>{{ $customfieldvalue->customfield->name }}</b></div>
    <div class="col-md-8">
        @if ($customfieldvalue->value)
            <i class="fas fa-fw fa-check"></i>
        @else
            <i class="fas fa-fw fa-times"></i>
        @endif
    </div>
</div>