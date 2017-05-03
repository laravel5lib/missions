<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAirportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('city');
            $table->string('country');
            $table->string('iata', 3)->index()->nullable();
            $table->string('icao', 4)->index()->nullable();
            $table->double('x');
            $table->double('y');
            $table->integer('elevation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('airports');
    }
}
