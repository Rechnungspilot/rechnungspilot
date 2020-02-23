<?php

namespace App\Templates;

use App\Templates\Standard;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Parental\HasChildren;

class Template extends Model
{
    use HasCompany, HasChildren;

    const NAME = 'Vergeben';

    const AVAILABLE = [
        Standard::class => Standard::NAME,
    ];

    const HEADER_LOGO_RIGHT = 0;
    const HEADER_LOGO_LEFT = 1;
    const HEADER_LOGO_FULL = 2;

    const HEADER_OPTIONS = [
        self::HEADER_LOGO_RIGHT => 'Logo rechts',
        self::HEADER_LOGO_LEFT => 'Logo links',
        self::HEADER_LOGO_FULL => 'Logo komplett',
    ];

    const HEADER_PATHS = [
        self::HEADER_LOGO_RIGHT => 'logo-right',
        self::HEADER_LOGO_LEFT => 'logo-left',
        self::HEADER_LOGO_FULL => 'logo-full',
    ];

    protected $uri = '/vorlagen';
    protected $stub = '';

    protected $appends = [
        'path',
        'stub',
        'url',
    ];

    protected $fillable = [
        'company_id',
        'header_type',
        'logo',
        'show_footer',
        'type',
    ];

    public function getHeaderPathAttribute()
    {
        return self::HEADER_PATHS[$this->header_type];
    }

    public function getPathAttribute()
    {
        return $this->uri . '/' . $this->id;
    }

    public function getStubAttribute()
    {
        return $this->stub;
    }

    public function getUrlAttribute()
    {
        return Storage::disk('s3')->url('logos/' . $this->logo);
    }

    public function setLogo(string $extension)
    {
        $this->logo = $this->company_id . '-' . $this->id . '.' . $extension;
    }

    public static function setup(int $companyId)
    {
        self::create([
            'type' => Standard::class,
            'company_id' => $companyId,
        ]);
    }
}
