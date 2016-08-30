<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('type');
            $table->string('source');
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('uploadables', function (Blueprint $table) {
            $table->uuid('upload_id')->index();
            $table->uuid('uploadable_id')->index();
            $table->uuid('uploadable_type')->index();
        });

        Schema::table('uploadables', function($table)
        {
            $table->foreign('upload_id')
                ->references('id')->on('uploads')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::drop('uploads');
        Schema::drop('uploadables');
    }
}
