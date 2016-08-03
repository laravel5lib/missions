<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploadables', function (Blueprint $table) {
            $table->uuid('upload_id')->index();
            $table->uuid('uploadable_id')->index();
            $table->uuid('uploadable_type')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('uploadables');
    }
}
