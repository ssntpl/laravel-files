<?php

// declare(strict_types=1);

namespace Ssntpl\LaravelFiles\Cloud;

use Illuminate\Contracts\Filesystem\Filesystem;
use Storage;
use Log;
class CloudAdapter implements Filesystem
{

    protected $cacheDisk;
    protected $remoteDisks;

    public function __construct($cacheDisk, array $remoteDisks)
    {
        $this->cacheDisk = Storage::disk($cacheDisk);
        $this->remoteDisks = array_map(function ($disk) {
            return Storage::disk($disk);
        }, $remoteDisks);
    }
    // Implement all required methods from the FilesystemAdapter interface
    function put($path, $contents, $options = []){
        
    }

    public function path($path){

    }
    public function putFile($path, $file = null, $options = []){

    }
    public function putFileAs($path, $file, $name = null, $options = []){

    }
    public function allDirectories($directory = null){

    }
    public function allFiles($directory = null){

    }
    public function append($path, $data){

    }
    public function copy($from, $to){

    }
    public function delete($paths){

    }
    public function deleteDirectory($directory){

    }
    public function directories($directory = null, $recursive = false){

    }
    public function exists($path){

    }
    public function files($directory = null, $recursive = false){

    }
    public function get($path){

    }
    public function getVisibility($path){

    }
    public function lastModified($path){

    }
    public function makeDirectory($path){

    }
    public function move($from, $to){

    }
    public function prepend($path, $data){

    }
    public function readStream($path){

    }
    public function setVisibility($path, $visibility){

    }
    public function size($path){

    }
    public function writeStream($path, $resource, array $options = []){

    }
}
    