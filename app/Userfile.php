<?php

namespace App;

use App\Contacts\Contact;
use App\Receipts\Invoice;
use App\Receipts\Order;
use App\Receipts\Quote;
use App\Receipts\Receipt;
use App\Traits\HasCompany;
use App\Traits\HasTags;
use App\Traits\HasUuid;
use D15r\ModelLabels\Traits\HasLabels;
use D15r\ModelPath\Traits\HasModelPath;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Userfile extends Model
{
    use HasCompany,
        HasLabels,
        HasModelPath,
        HasTags;

    const ROUTE_NAME = 'userfiles';
    const TYPE = 'userfiles';

    protected $appends = [
        'url',
        'tagsString',
    ];

    public const TYPES = [
        -1 => 'Ohne Zuordnung',
        Receipt::class => 'Belege',
        Contact::class => 'Kontakte',
        User::class => 'Team',
    ];

    public const MIME_TYPES = [
        'jpeg',
        'bmp',
        'png',
        'pdf',
    ];

    protected $uuidColumn = 'filename';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'extension',
        'fileable_id',
        'fileable_type',
        'filename',
        'mime',
        'name',
        'original_name',
        'preview',
        'size',
        'thumbnail',
        'user_id',
    ];

    public static function fromUploadedFile(UploadedFile $file, Model $fileable = null) : self
    {
        $attributes['company_id'] = auth()->user()->company_id;
        $attributes['mime'] = $file->getClientMimeType();
        $attributes['name'] = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $attributes['extension'] = $file->extension();
        $attributes['original_name'] = $file->getClientOriginalName();
        $attributes['size'] = $file->getSize();
        $attributes['user_id'] = auth()->user()->id;
        if (! is_null($fileable))
        {
            $attributes['fileable_type'] = get_class($fileable);
            $attributes['fileable_id'] = $fileable->id;
        }

        $userfile = new Userfile($attributes);
        $userfile->setFilename($attributes['extension']);

        if ($path = $file->storeAs(config('app.userfiles_path'), $userfile->filename, ['disk' => config('app.storage_disk_userfiles')]))
        {
            if (is_null($fileable))
            {
                $userfile->save();
            }
            else
            {
                $fileable->userfiles()->save($userfile);
            }
        }

        return $userfile->load('fileable');
    }

    protected static function labels() : array
    {
        return [
            'nominativ' => [
                'singular' => 'Datei',
                'plural' => 'Dateien',
            ],
        ];
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function fileable()
    {
        return $this->morphTo('fileable');
    }

    public function isDeletable() : bool
    {
        return true;
    }

    public function getUrlAttribute()
    {
        return config('app.userfiles_url') . $this->filename;
    }

    public function scopeSearch($query, $searchtext)
    {
        if ($searchtext == '') {
            return $query;
        }

        return $query->where( function ($query) use($searchtext)
        {
            $query
                ->orWhere('name', 'LIKE', '%' . $searchtext . '%')
                ->orWhere('original_name', 'LIKE', '%' . $searchtext . '%')
                ->orWhere('filename', 'LIKE', '%' . $searchtext . '%');
        });
    }

    public function scopeType($query, $type)
    {
        if ($type == 0)
        {
            return $query;
        }

        return $query->where('fileable_type', $type == -1 ? null : $type );
    }

    public function setFilename(string $extension)
    {
        $uuid = Str::uuid();
        if ($this->checkFilename($uuid))
        {
            $this->setUUID();
        }

        $this->filename = $uuid . '.' . $extension;
    }

    protected function checkFilename(string $uuid)
    {
        return self::where('filename', 'LIKE', $uuid . '.%')->exists();
    }

}
