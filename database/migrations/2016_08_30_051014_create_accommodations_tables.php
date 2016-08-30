<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccommodationsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accommodations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('address_one')->nullable();
            $table->string('address_two')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('country_code');
            $table->string('email')->nullable();
            $table->string('url')->nullable();
            $table->uuid('region_id')->index();
            $table->string('short_desc')->nullable();
            $table->timestamp('check_in_at')->nullable();
            $table->timestamp('check_out_at')->nullable();
            $table->timestamps();
        });

        Schema::table('accommodations', function($table)
        {
            $table->foreign('region_id')
                ->references('id')->on('regions')
                ->onDelete('cascade');
        });

        Schema::create('occupants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('room_no');
            $table->uuid('accommodation_id')->index();
            $table->uuid('reservation_id')->index();
            $table->boolean('room_leader')->default(0);
            $table->timestamps();
        });

        Schema::table('occupants', function($table)
        {
            $table->foreign('accommodation_id')
                ->references('id')->on('accommodations')
                ->onDelete('cascade');

            $table->foreign('reservation_id')
                ->references('id')->on('reservations')
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

        Schema::drop('accommodations');
        Schema::drop('occupants');
    }
}
