<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create(config('files.table', 'files'), function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('owner_id');
            $table->string('owner_type');
            $table->index(['owner_id', 'owner_type'], 'files_owner_id_owner_type_index');

            $table->string('type');                     // FILE::Type (handwritten notes, signature, doc_sigmet)
            $table->string('name')->nullable();         // Name of the file. Usually it is in the key.
            $table->string('title')->nullable();        // Title displayed to the user.

            $table->string('key');                      // File key (path) with which it is stored in storage.
            $table->string('disk');                     // Storage disk (local, aws, ftp, etc.) null means default
            $table->string('checksum')->nullable();

            $table->timestamps();

            $table->index(['key', 'disk'], 'files_key_disk_index');
            $table->index('type');
            $table->index('name');
        });
    }
};
