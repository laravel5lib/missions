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

         Schema::create('itinerary_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('type');
            $table->uuid('attachment_id')->nullable();
            $table->string('attachment_type')->nullable();
            $table->timestamp('occurs_at');
            $table->timestamps();
            $table->softDeletes();
        });

         // e21bad87-d9a9-4819-ba31-b70d5b18a1dc

         Schema::create('itinerary_item', function (Blueprint $table) {
            $table->uuid('itinerary_id')->index();
            $table->uuid('itinerary_item_id')->index();
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
        Schema::drop('itinerary_items');
        Schema::drop('itinerary_item');
    }
}
