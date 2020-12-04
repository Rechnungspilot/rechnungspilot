<?php

namespace App\Models\CustomFields;

use App\Receipts\Invoice;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

class CustomField extends Model
{
    use HasCompany;

    const INPUT_TYPES = [
        'text' => 'Text',
        'checkbox' => 'Checkbox',
        'select' => 'Select',
    ];

    const RULES = [
        'text' => 'nullable|string',
        'checkbox' => 'nullable|string',
        'select' => 'nullable|string',
    ];

    protected $appends = [
        'optionsAsString',
    ];

    protected $fillable = [
        'company_id',
        'default',
        'default_value',
        'for',
        'info',
        'input_type',
        'name',
        'options',
        'type',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    /**
     * The booting method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function($model)
        {
            if (! isset($model->default))
            {
                $model->default = false;
            }

            return true;
        });
    }

    public static function for(Model $model) : Collection
    {
        return self::where('for', self::getForFromModel($model))
            ->whereNotIn('id', $model->customfields->pluck('custom_field_id'))
            ->orderBy('name', 'ASC')
            ->get();
    }

    public static function defaultsFor(Model $model) : Collection
    {
        return self::where('for', self::getForFromModel($model))
            ->where('default', 1)
            ->get();
    }

    public static function getForFromModel(Model $model) : string
    {
        return substr($model->uri, 1);
    }

    public function getInputTypeNameAttribute()
    {
        return Arr::get(self::INPUT_TYPES, $this->attributes['input_type'], '');
    }

    public function getKeyAttribute() : string
    {
        return 'custom_field_' . $this->id;
    }

    public function getOptionsAsStringAttribute()
    {
        return is_array($this->options) ? join("\n", $this->options) : $this->attributes['options'];
    }

    public static function getRule(string $inputType) : string
    {
        return self::RULES[$inputType];
    }

    public function values() : HasMany
    {
        return $this->hasMany(CustomFieldValue::class);
    }

    public function isDeletable()
    {
        return ! $this->values()->exists();
    }

    public function scopeSearch($query, $searchtext)
    {
        if (! $searchtext) {
            return $query;
        }

        return $query->where('name', 'LIKE', '%' . $searchtext . '%');
    }
}
