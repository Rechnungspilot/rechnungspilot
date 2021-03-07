<div class="col-sm-4 d-flex justify-content-between">
    <label class="col-form-label col-form-label-sm" for="{{ $customfieldvalue->key }}">{{ $customfieldvalue->name }}</label>
    <div class="d-flex align-items-center">
        @if($customfieldvalue->customfield->info)
            <i class="fas fa-fw fa-info-circle mr-1 pointer" data-toggle="popover" data-placement="left" data-content="{{ $customfieldvalue->customfield->info }}" title="Info"></i>
        @endif
        <i class="fas fa-fw fa-trash text-danger pointer" id="delete-{{ $customfieldvalue->key }}" onclick="axios.delete('<?php echo route('customfieldvalue.destroy', ['customfieldvalue' => $customfieldvalue->id]); ?>').then(function (response) { $('#form-group-{{ $customfieldvalue->key }}').remove(); });"></i>
    </div>
</div>