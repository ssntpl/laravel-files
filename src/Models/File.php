<?php

namespace Ssntpl\LaravelFiles\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    const TYPE_DEFAULT = 'default';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['owner_id', 'owner_type'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'owner_id', 'owner_type'];

    public function getTable()
    {
        return config('files.table', 'files');
    }

    public function owner()
    {
        return $this->morphTo();
    }

    public function getBase64Attribute()
    {
        return base64_encode($this->contents);
    }

    public function setBase64Attribute($data)
    {
        return $this->setContentsAttribute(base64_decode($data));
    }

    public function getContentsAttribute()
    {
        return $this->exists() ? Storage::disk($this->disk)->get($this->key) : null;
    }

    public function setContentsAttribute($contents)
    {
        return Storage::disk($this->disk)->put($this->key, $contents);
    }

    public function getUrlAttribute()
    {
        return Storage::disk($this->disk)->url($this->key);
    }

    public function exists()
    {
        return Storage::disk($this->disk)->exists($this->key);
    }

    /**
     * Get the instance as a string.
     *
     * @return array
     */
    public function __toString()
    {
        return $this->url;
    }
}
