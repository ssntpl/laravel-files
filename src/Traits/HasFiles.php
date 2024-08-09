<?php

namespace Ssntpl\LaravelFiles\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Ssntpl\LaravelFiles\Models\File;
use Exception;

/**
 * Adds
 */
trait HasFiles
{
    public function files()
    {
        return $this->morphMany(File::class, 'owner');
    }

    public function createFile(array $attributes = [])
    {
        // Throw exception if $attributes['type'] is missing.
        if (empty($attributes['type'])) {
            if (defined(get_class($this) . '::FILE_TYPE_DEFAULT')) {
                $attributes['type'] = get_class($this)::FILE_TYPE_DEFAULT;
            } else {
                throw new Exception('Cannot create file. File `type` is missing.');
            }
        }

        // Find file prefix
        $file_prefix = null;
        $model = $this;
        while (!$file_prefix) {
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

        if (!empty($attributes['base64'])) {
            $attributes['contents'] = base64_decode($attributes['base64']);
        }

        if (!empty($attributes['contents'])) {
            $storage->put($attributes['key'], $attributes['contents']);
            $attributes['checksum'] = hash(config('files.checksum', 'md5'), $attributes['contents']);
        }

        $file_attributes = [];

        foreach (['type', 'name', 'title', 'checksum'] as $field) {
            if (!empty($attributes[$field])) {
                $file_attributes[$field] = $attributes[$field];
            }
        }

        return $this->files()->updateOrCreate([
            'disk' => $attributes['disk'],
            'key' => $attributes['key']
        ], $file_attributes);
    }

    public function getFileAttribute()
    {
        return $this->files()->first();
    }
}
