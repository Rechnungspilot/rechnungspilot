<?php

namespace App\Models\CustomFields;

use App\Collections\CustomFields\CustomFieldValueCollection;
use App\Models\CustomFields\CustomField;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

class CustomFieldValue extends Model
{
    use HasCompany;

    protected $fillable = [
        'company_id',
        'custom_field_id',
        'customfieldable_id',
        'customfieldable_type',
        'value',
    ];

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new CustomFieldValueCollection($models);
    }

    public function getKeyAttribute() : string
    {
        return 'custom_field_value_' . $this->id;
    }

    public function getNameAttribute() : string
    {
        return $this->customfield->name;
    }

    public function getBaseViewPath(string $view) : string
    {
        return $this->getViewPath() . $view;
    }

    public function getEditViewPathAttribute() : string
    {
        return $this->getViewPath('edit');
    }

    public function getShowViewPathAttribute() : string
    {
        return $this->getViewPath('show');
    }

    protected function getViewPath(string $type = null) : string
    {
        return 'customfieldvalue.input_type.' . ($type ? $type . '.' . $this->customfield->input_type : '');
    }

    public function getValueAttribute()
    {
        return $this->customfield->input_type == 'select' ? Arr::get($this->customfield->options, $this->attributes['value'], '-') : $this->attributes['value'];
    }

    public function customfield() : BelongsTo
    {
        return $this->belongsTo(CustomField::class, 'custom_field_id');
    }

    public function isDeletable() : bool
    {
        return true;
    }
}
