<?php

namespace App;

use App\Traits\HasComments;
use App\Traits\HasCompany;
use App\Traits\HasTags;
use App\Traits\HasUserfiles;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasComments, HasCompany, HasRoles, HasTags, Notifiable, HasUserfiles;

    protected $appends = [
        'initials',
        'name',
        'path',
        'tagsString',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address',
        'bankname',
        'bic',
        'city',
        'company_id',
        'email',
        'firstname',
        'iban',
        'lastname',
        'mobilenumber',
        'number',
        'password',
        'phonenumber',
        'postcode',
        'uuid',
        'hex_color_code',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'uuid',
    ];

    protected $guard_name = 'web';

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
            $model->hex_color_code = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);

            return true;
        });
    }

    public static function getFromUuid(string $uuid) : self
    {
        return self::where('uuid', $uuid)->firstOrFail();
    }

    public function getNameAttribute()
    {
        return $this->lastname . ', ' . $this->firstname;
    }

    public function getInitialsAttribute()
    {
        return $this->attributes['firstname']{0} . $this->attributes['lastname']{0};
    }

    public function getCreatePasswordUrlAttribute() : string
    {
        return URL::temporarySignedRoute('password.create', now()->addHours(24), ['user' => $this->id]);
    }

    public function getStorePasswordUrlAttribute() : string
    {
        return URL::temporarySignedRoute('password.create', now()->addHours(24), ['user' => $this->id]);
    }

    public function getIsAdminAttribute()
    {
        return ($this->id === 1);
    }

    public function getPathAttribute()
    {
        return route('team.show', ['team' => $this->id]);
    }

    public function isDeletable() : bool
    {
        return true;
    }

    public function scopeSearch($query, $searchtext)
    {
        if ($searchtext == '') {
            return $query;
        }

        $query->where( function ($query) use($searchtext)
        {
            $query
                ->orWhere('firstname', 'LIKE', '%' . $searchtext . '%')
                ->orWhere('lastname', 'LIKE', '%' . $searchtext . '%')
                ->orWhere('email', 'LIKE', '%' . $searchtext . '%');
        });
    }

    public function setPassword(string $password)
    {
        $this->password = Hash::make($password);
        $this->update();
    }

    protected function checkUUID(string $uuid)
    {
        return self::where('uuid', $uuid)->exists();
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
