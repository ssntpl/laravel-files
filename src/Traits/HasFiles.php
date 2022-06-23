<?php

namespace Ssntpl\LaravelFiles\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Ssntpl\LaravelFiles\Models\File;

/**
 * Adds
 */
trait HasFiles
{
    public function createFile(array $attributes = [])
    {
        // Use 'default' if $attributes['type'] is missing.
        if (empty($attributes['type'])) {
            $attributes['type'] = $this->default_file_type;
        }

        // Find file prefix
        $file_prefix = null;
        $model = $this;
        while (! $file_prefix) {
            if (method_exists($model, 'getFilePrefixAttribute')) {
                $file_prefix = $model->getFilePrefixAttribute();
            } elseif (isset($model->file_prefix)) {
                $file_prefix = $model->file_prefix;
            } elseif (method_exists($model, 'owner')) {
                $model = $model->owner;
            } else {
                $file_prefix = 'files';
            }
        }

        // Add directory separator to the file_prefix
        $file_prefix = rtrim($file_prefix, '/') . '/';

        // Set key
        if (empty($attributes['key'])) {
            $name = $attributes['name'] ?? Str::uuid();
            $attributes['key'] = $file_prefix . $name;
        }

        // Set disk
        if (empty($attributes['disk'])) {
            if (isset($this->disk)) {
                $attributes['disk'] = $this->disk;
            } else {
                $attributes['disk'] = config('filesystems.default');
            }
        }

        $storage = Storage::disk($attributes['disk']);

        if (! empty($attributes['base64'])) {
            $attributes['contents'] = base64_decode($attributes['base64']);
        }

        if (! empty($attributes['contents'])) {
            $storage->put($attributes['key'], $attributes['contents']);
            $attributes['checksum'] = hash(config('files.checksum', 'md5'), ($attributes['contents']));
        }

        $file_attributes = [];

        foreach (['type', 'name', 'title', 'checksum'] as $field) {
            if (! empty($attributes[$field])) {
                $file_attributes[$field] = $attributes[$field];
            }
        }

        return $this->files()->updateOrCreate([
            'disk' => $attributes['disk'],
            'key' => $attributes['key'],
        ], $attributes);
    }

    public function files($type = null)
    {
        if ($type) {
            return $this->morphMany(File::class, 'owner')->whereType($type);
        }

        return $this->morphMany(File::class, 'owner');
    }

    public function file($type = null)
    {
        $type = $type ?: $this->default_file_type;

        return $this->morphOne(File::class, 'owner')->whereType($type)->sole();
    }

    public function getFileAttribute()
    {
        return $this->files($this->default_file_type)->sole();
    }

    public function getDefaultFileTypeAttribute()
    {
        if (defined(get_class($this) . '::FILE_TYPE_DEFAULT')) {
            return get_class($this)::FILE_TYPE_DEFAULT;
        }

        return config('files.model', 'Ssntpl\LaravelFiles\Models\File')::TYPE_DEFAULT;
    }
}
