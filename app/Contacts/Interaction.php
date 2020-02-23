<?php

namespace App\Contacts;

use App\Contacts\InteractionType;
use App\Contacts\Person;
use App\Traits\HasCompany;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Interaction extends Model
{
    use HasCompany;

    const BASE_ROUTE_PATH = 'interaction';

    protected $appends = [
        'path',
    ];

    protected $dates = [
        'at',
    ];

    protected $fillable = [
        'at',
        'company_id',
        'contact_id',
        'interaction_id',
        'interaction_type_id',
        'interactionable_id',
        'interactionable_type',
        'name',
        'person_id',
        'text',
        'user_id',
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
            if (! $model->interaction_type_id) {
                $model->interaction_type_id = InteractionType::first()->id;
            }
            if (! $model->at) {
                $model->at = now();
            }
            if (! $model->name) {
                $model->name = $model->type->name . ' vom ' . $model->at->format('d.m.Y');
            }
            if (! $model->text) {
                $model->text = null;
            }

            return true;
        });

        static::updating( function ($model) {
            if ($model->person_id == 0) {
                $model->person_id = null;
            }
        });
    }

    public function isDeletable() : bool
    {
        return ! $this->interactions()->exists();
    }

    public function getPathAttribute()
    {
        return route('interaction.show', [
            'interaction' => $this->id
        ]);
    }

    public function contact() : BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function interactionable() : MorphTo
    {
        return $this->morphTo('interactionable');
    }

    public function interactions() : HasMany
    {
        return $this->hasMany(self::class);
    }

    public function person() : BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function type() : BelongsTo
    {
        return $this->belongsTo(InteractionType::class, 'interaction_type_id');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeInteraction(Builder $query, $id) : Builder
    {
        if (! $id)
        {
            return $query;
        }

        return $query->where('interaction_id', $id);
    }
}
