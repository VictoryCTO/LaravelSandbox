<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FileAwsPaths extends Migration
{
  public function up()
  {
    Schema::table('file_resources', function(Blueprint $table) {
      $table->boolean('saved_in_aws')->nullable()->default(0);
      $table->string('primary_aws_path')->nullable();
      $table->string('primary_aws_url')->nullable();
    });
  }

  public function down()
  {
    Schema::table('file_resources', function(Blueprint $table) {
      $table->dropColumn('saved_in_aws');
      $table->dropColumn('primary_aws_path');
    });
  }
}
