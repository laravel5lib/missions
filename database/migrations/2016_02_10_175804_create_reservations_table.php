<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('given_names');
            $table->string('surname');
            $table->string('gender');
            $table->string('status');
            $table->string('shirt_size');
            $table->date('birthday');
            $table->uuid('user_id')->index();
            $table->uuid('trip_id')->index();
            $table->uuid('rep_id')->index()->nullable();
            $table->uuid('passport_id')->index()->nullable();
            $table->uuid('visa_id')->index()->nullable();
            $table->uuid('avatar_upload_id')->nullable();
            $table->integer('companion_limit')->unsigned()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reservations');
    }
}
