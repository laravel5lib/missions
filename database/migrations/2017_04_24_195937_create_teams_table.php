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
            $table->uuid('id')->primary();
            $table->uuid('reservation_id')->index();
            $table->uuid('team_squad_id')->index();
            $table->boolean('leader');
            $table->timestamps();
        });

        // groups, regions, campaigns
        Schema::create('teamables', function (Blueprint $table) {
            $table->uuid('team_id')->index();
            $table->uuid('teamable_id')->index();
            $table->uuid('teamable_type')->index();
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
    }
}
