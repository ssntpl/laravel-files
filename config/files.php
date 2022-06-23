<?php
// config for Ssntpl/LaravelFiles
return [

    /*
     * When using the "HasFiles" trait from this package, we need to know which
     * Eloquent model should be used to retrieve your files. Of course, it
     * is often just the "File" model but you may use whatever you like.
     *
     * The model you want to use as a File model needs to implement the
     * `Ssntpl\LaravelFiles\Contracts\File` contract.
     */

    'model' => Ssntpl\LaravelFiles\Models\File::class,

    /*
     * When using the "HasFiles" trait from this package, we need to know which
     * table should be used to retrieve your files. We have chosen a basic
     * default value but you may easily change it to any table you like.
     */

    'table' => 'files',

    /*
     * Define the hashing algorithm to calculate the checksum of the file content.
     * Most commonly used hashing algorithm are md5, sha1, sha256. For a complete
     * list of supported hashing algorithms, check hash_algos().
     */

    'checksum' => 'md5',

];
