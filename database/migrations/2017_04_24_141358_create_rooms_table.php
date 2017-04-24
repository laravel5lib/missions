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
            $table->integer('occupancy');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('rooms', function (Blueprint $table) {
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
        
        Schema::drop('rooms');
        Schema::drop('room_types');
    }
}
