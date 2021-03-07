<?php

namespace App\Traits;

use App\Models\CustomFields\CustomField;
use App\Models\CustomFields\CustomFieldValue;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasCustomFields
{
    public static function bootHasCustomFields()
    {
        static::created(function ($model)
        {
            foreach (CustomField::DefaultsFor($model) as $defaultCustomfield) {
                $model->customfields()->create([
                    'company_id' => $defaultCustomfield->company_id,
                    'custom_field_id' => $defaultCustomfield->id,
                    'value' => $defaultCustomfield->default_value,
                ]);
            }
        });
    }

    public static function indexPathCustomfields() : string
    {
        return route('customfield.index', [
            'type' => self::TYPE,
        ]);
    }

    public function getCustomfieldsPathAttribute()
    {
        return route('customfieldvalue.index', [
            'model' => $this->id,
            'type' => self::TYPE,
        ]);
    }

    public function customfields() : MorphMany
    {
        return $this->morphMany(CustomFieldValue::class, 'customfieldable')
            ->select('custom_field_values.*')
            ->with('customfield')
            ->join('custom_fields', 'custom_field_values.custom_field_id', '=', 'custom_fields.id')
            ->orderBy('custom_fields.name', 'ASC');
    }

    public function attachCustomfield(CustomField $customfield, $value = null) : CustomFieldValue
    {
        return $this->customfields()->create([
            'company_id' => $customfield->company_id,
            'custom_field_id' => $customfield->id,
            'value' => $value,
        ]);
    }
}