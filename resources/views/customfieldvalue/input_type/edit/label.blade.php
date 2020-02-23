<div class="d-flex justify-content-between">
    <label for="{{ $customfieldvalue->key }}">{{ $customfieldvalue->name }}</label>
    <div>
        @if($customfieldvalue->customfield->info)
            <i class="fas fa-fw fa-info-circle mr-1 pointer" data-toggle="popover" data-placement="left" data-content="{{ $customfieldvalue->customfield->info }}" title="Info"></i>
        @endif
        <i class="fas fa-fw fa-trash text-danger pointer" id="delete-{{ $customfieldvalue->key }}" onclick="axios.delete('<?php echo route('customfieldvalue.destroy', ['customfieldvalue' => $customfieldvalue->id]); ?>').then(function (response) { $('#form-group-{{ $customfieldvalue->key }}').remove(); });"></i>
    </div>
</div>