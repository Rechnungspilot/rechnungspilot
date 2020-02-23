<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    protected $uuidColumn = 'uuid';

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
            $model->setUUID();

            return true;
        });
    }

    public static function getFromUuid(string $uuid) : self
    {
        return self::where($this->uuidfiled, $uuid)->firstOrFail();
    }

    protected function checkUUID(string $uuid)
    {
        return self::where($this->uuidfiled, $uuid)->exists();
    }

    protected function setUUID()
    {
        $uuid = Str::uuid();
        if ($this->checkUUID($uuid))
        {
            $this->setUUID();
        }

        $this->uuid = $uuid;
    }
}