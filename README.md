
# Associate files with Eloquent models

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ssntpl/laravel-files.svg?style=flat-square)](https://packagist.org/packages/ssntpl/laravel-files)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/ssntpl/laravel-files/run-tests?label=tests)](https://github.com/ssntpl/laravel-files/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/ssntpl/laravel-files/Check%20&%20fix%20styling?label=code%20style)](https://github.com/ssntpl/laravel-files/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/ssntpl/laravel-files.svg?style=flat-square)](https://packagist.org/packages/ssntpl/laravel-files)

This is a simple package to associate files with your eloquent model in laravel. 

## Installation

You can install the package via composer:

```bash
composer require ssntpl/laravel-files
```

Run the migrations with:

```bash
php artisan migrate
```

Optionally, You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-files-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-files-config"
```

This is the contents of the published config file:

```php
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
```

## Usage

Add the `HasFiles` trait to your model.

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use Ssntpl\LaravelFiles\Traits\HasFiles;

class User extends Authenticatable
{
    use HasFiles;
}
```

Add new file to the model.

```php
$model = User::find(1);

$file = $model->createFile([
    
    // type: Optional. If missing, the deault type of the model is used.
    'type' => 'avatar', 

    // name: Optional. Represents the file name. If missing, a random uuid is generated.
    'name' => 'my_avatar.png',  

    // key: Optional. If missing the key is auto-generated from FilePrefix attribute and name of the file.
    'key' => 'avatar/user/1/my_avatar.png', 

    // disk: Optional. Represents the disk on which the file is stored. If missing the default disk is used.   
    'disk' => 's3', 

    // base64: Optional. If present the string is decoded and written to disk.
    'base64' => 'base64 encoded string of the content of the file.',

    // contents: Optional. base64 takes precedence over contents key. If both base64 and contents key are missing then you can add the contents to the file later.
    'contents' => 'you can directly provide the contents instead of the base64 string',
]);
```

Defining the FilePrefix for the model.
```php
class Flight extends Model 
{
    HasFiles;

    Protected $file_prefix = 'flights';
}
```

Defining FilePrefix dynamically. Sometimes we don't need a fixed path to store all the files of the model. You can use the FilePrefix attribute to create dynamic file prefix.
```php
class Flight extends Model 
{
    HasFiles;

    public function getFilePrefixAttribute()
    {
        return 'flights/' . $this->id;       
    }
}
```

Accessing the File model.
```php
$file->url; // Returns the url() of the file.

$file->exists(); // Boolean. Checks if the file exists on the disk.

echo $file; // Prints the url of the file.

echo $file->base64; // Prints the base64 encoded string of the file contents.

$file->base64 = 'base64 contents'; // Writes the content to the disk.

echo $file->contents; // Print the file contents.

$file->contents = 'file contents'; // Writes the contents to the disk.
```

Fetch single file. An excpeption is thrown if none, or more than one file is available.
```php
$model->file;           // Fetches single file of default type. 

$model->file();         // Fetches single file of default type.

$model->file($type);    // Fetches single file of $type type.
```

Fetch multiple file relation.
```php
$files = $model->files();   // Fetches relation to all the files belonging to the model.

$files->get(); // Fetches all the files of any type that belong to the model.

$files = $model->files($type);   // Fetches relation to all the files of type $type that belong to the model.

$files->get(); // Fetches all the files of type $type.

```

## Testing

```bash
composer test
```

## TODO
- [ ] Declare the `Ssntpl\LaravelFiles\Contracts\File` contract
- [ ] Add option to define default disk for every model
- [ ] Add path() method that returns the full path of the file
- [ ] Make File model sub-class of `Illuminate\Http\File` and see if all the methods work fine.
- [ ] See if destroy/delete method can be modified in trait to handle the file objects

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.


## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Sam](https://github.com/ssntpl)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
