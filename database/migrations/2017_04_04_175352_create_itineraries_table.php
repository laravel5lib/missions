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
            $table->uuid('curator_id');
            $table->string('curator_type');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('activity_type_id')->index();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('participant_id');
            $table->string('participant_type');
            $table->timestamp('occurred_at');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('activity_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
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
        Schema::dropIfExists('itineraries');
        Schema::dropIfExists('activities');
        Schema::dropIfExists('activity_types');
        Schema::dropIfExists('activitables');
    }
}
