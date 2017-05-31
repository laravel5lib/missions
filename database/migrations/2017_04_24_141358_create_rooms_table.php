<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooming_plans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('group_id')->index();
            $table->uuid('campaign_id')->index();
            $table->string('name');
            $table->string('short_desc');
            $table->boolean('locked')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('rooms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('room_type_id');
            $table->string('label')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('room_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->json('rules')->nullable(); // gender, status, occupancy
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('rooming_plan_room_type', function (Blueprint $table) {
            $table->uuid('rooming_plan_id')->index();
            $table->uuid('room_type_id')->index();
            $table->integer('available_rooms')->default(0);
            $table->timestamps();
        });

        Schema::create('occupants', function (Blueprint $table) {
            $table->uuid('reservation_id')->index();
            $table->uuid('room_id')->index();
            $table->boolean('room_leader')->default(false);
            $table->timestamps();
        });

        // rooming plans, accomodations
        Schema::create('roomables', function (Blueprint $table) {
            $table->uuid('room_id')->index();
            $table->uuid('roomable_id')->index();
            $table->string('roomable_type');
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->foreign('room_type_id')
                ->references('id')->on('room_types')
                ->onDelete('cascade');
        });

        Schema::table('occupants', function (Blueprint $table) {
            $table->foreign('room_id')
                ->references('id')->on('rooms')
                ->onDelete('cascade');

            $table->foreign('reservation_id')
                ->references('id')->on('reservations')
                ->onDelete('cascade');
        });

        Schema::table('rooming_plans', function (Blueprint $table) {
            $table->foreign('group_id')
                ->references('id')->on('groups')
                ->onDelete('cascade');

            $table->foreign('campaign_id')
                ->references('id')->on('campaigns')
                ->onDelete('cascade');
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
        
        Schema::dropIfExists('rooming_plans');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('room_types');
        Schema::dropIfExists('rooming_plan_room_type');
        Schema::dropIfExists('roomables');
        Schema::dropIfExists('occupants');
    }
}
