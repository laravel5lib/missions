<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItinerariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itineraries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->uuid('itinerant_id');
            $table->string('itinerant_type');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('participant_id');
            $table->string('participant_type');
            $table->timestamp('occured_at');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('activitables', function (Blueprint $table) {
            $table->string('activitable_type');
            $table->uuid('activitable_id')->index();
            $table->uuid('activity_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('itineraries');
        Schema::drop('activities');
        Schema::drop('activitables');
    }
}
