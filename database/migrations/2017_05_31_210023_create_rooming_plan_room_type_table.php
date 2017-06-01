<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomingPlanRoomTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooming_plan_room_type', function (Blueprint $table) {
            $table->uuid('rooming_plan_id')->index();
            $table->uuid('room_type_id')->index();
            $table->integer('available_rooms')->default(0);
            $table->timestamps();
        });

        Schema::table('rooming_plan_room_type', function (Blueprint $table) {
            $table->foreign('rooming_plan_id')
                ->references('id')->on('rooming_plans')
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
        Schema::dropIfExists('rooming_plan_room_type');
    }
}
