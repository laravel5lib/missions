<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->json('rules')->nullable();
            $table->timestamps();
        });

        Schema::create('teams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('type_id')->nullable();
            $table->string('callsign');
            $table->boolean('locked')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('team_squads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('team_id');
            $table->string('callsign');
        });

        Schema::create('team_members', function (Blueprint $table) {
            $table->uuid('reservation_id');
            $table->uuid('team_squad_id');
            $table->boolean('leader');
            $table->timestamps();
        });

        Schema::create('teamables', function (Blueprint $table) {
            $table->uuid('team_id')->index();
            $table->uuid('teamable_id')->index();
            $table->string('teamable_type');
        });

        Schema::table('teams', function ($table) {
            $table->foreign('type_id')
                ->references('id')->on('team_types')
                ->onDelete('set null');
        });

        Schema::table('team_members', function ($table) {
            $table->foreign('reservation_id')
                ->references('id')->on('reservations')
                ->onDelete('cascade');

            $table->foreign('team_squad_id')
                ->references('id')->on('team_squads')
                ->onDelete('cascade');
        });

        Schema::table('team_squads', function ($table) {
            $table->foreign('team_id')
                ->references('id')->on('teams')
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
        
        Schema::dropIfExists('team_types');
        Schema::dropIfExists('teams');
        Schema::dropIfExists('team_squads');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('teamables');
    }
}
