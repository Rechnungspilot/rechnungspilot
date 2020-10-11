<?php

namespace App;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasCompany;

    protected $fillable = [
        'company_id',
        'name',
        'order_column',
        'slug',
        'type',
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
            $model->slug = Str::slug($model->name);

            return true;
        });

        static::updating(function($model)
        {
            $model->slug = Str::slug($model->name);

            return true;
        });
    }

    public static function findFromString(string $name, string $type = null)
    {
        return static::query()
            ->where('company_id', auth()->user()->company_id)
            ->where('name', $name)
            ->where('type', $type)
            ->first();
    }

    public function getLabelAttribute()
    {
        return $this->name;
    }

    public function getBadgeAttribute() : string
    {
        return '<span class="badge badge-secondary">' . $this->name . '</span>';
    }

    public function scopeSearch($query, $searchtext)
    {
        if ($searchtext == '') {
            return $query;
        }

        $query->where('name', 'LIKE', '%' . $searchtext . '%');
    }

    public function scopeWithType(Builder $query, string $type = null): Builder
    {
        if (is_null($type)) {
            return $query;
        }

        return $query->where('type', $type);
    }

    public function scopeContaining(Builder $query, string $name): Builder
    {
        return $query->where('name', 'LIKE', '%' . strtolower($name) . '%');
    }
}
