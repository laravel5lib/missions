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
        Schema::create('teams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('type_id')->nullable()->index();
            $table->string('callsign');
            $table->boolean('locked')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('team_squads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('team_id')->index();
            $table->string('callsign');
        });

        Schema::create('team_members', function (Blueprint $table) {
            $table->uuid('reservation_id')->index();
            $table->uuid('team_squad_id')->index();
            $table->boolean('leader');
            $table->timestamps();
        });

        Schema::create('teamables', function (Blueprint $table) {
            $table->uuid('team_id')->index();
            $table->uuid('teamable_id')->index();
            $table->uuid('teamable_type')->index();
        });

        Schema::create('team_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->json('rules')->nullable();
            $table->timestamps();
        });

        Schema::table('teams', function (Blueprint $table) {
            $table->foreign('type_id')
                ->references('id')->on('team_types')
                ->onDelete('set null');
        });

        Schema::table('team_members', function (Blueprint $table) {
            $table->foreign('reservation_id')
                ->references('id')->on('reservations')
                ->onDelete('cascade');

            $table->foreign('team_squad_id')
                ->references('id')->on('team_squads')
                ->onDelete('cascade');
        });

        Schema::table('team_squads', function (Blueprint $table) {
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
        Schema::drop('teams');
        Schema::drop('team_squads');
        Schema::drop('team_members');
        Schema::drop('teamables');
        Schema::drop('team_types');
    }
}
