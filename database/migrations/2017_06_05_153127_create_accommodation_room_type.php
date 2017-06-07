<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccommodationRoomType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accommodation_room_type', function (Blueprint $table) {
            $table->uuid('accommodation_id')->index();
            $table->uuid('room_type_id')->index();
            $table->integer('available_rooms')->default(0);
            $table->timestamps();
        });

        Schema::table('accommodation_room_type', function (Blueprint $table) {
            $table->foreign('accommodation_id')
                ->references('id')->on('accommodations')
                ->onDelete('cascade');

            $table->foreign('room_type_id')
                ->references('id')->on('room_types')
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
        Schema::dropIfExists('accommodation_room_type');
    }
}
