<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FileResources extends Migration
{
  public function up()
  {
    Schema::create('file_resources', function (Blueprint $table) {
      $table->bigIncrements('resource_id');
      $table->string('resource_type')->nullable();
      $table->string('resource_key')->nullable();
      $table->string('hash_identifier')->nullable();
      $table->string('file_extension')->nullable();
      $table->integer('filesize_bytes')->nullable();
      $table->boolean('locally_saved')->nullable()->default(0);
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('file_resources');
  }
}
