<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSquadMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('squad_members', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->integer('squad_id')->unsigned()->index();
            $table->uuid('reservation_id')->index();
            $table->string('group')->nullable();
            $table->timestamps();
        });

        Schema::table('squad_members', function (Blueprint $table) {
            $table->foreign('squad_id')
                  ->references('id')->on('squads')
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
        Schema::dropIfExists('squad_members');
    }
}
