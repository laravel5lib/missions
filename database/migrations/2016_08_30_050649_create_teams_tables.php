<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamsTables extends Migration
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
            $table->uuid('region_id')->index();
            $table->string('call_sign');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::table('teams', function($table)
        {
            $table->foreign('region_id')
                ->references('id')->on('regions')
                ->onDelete('cascade');
        });

        Schema::create('team_members', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('team_id')->index();
            $table->uuid('assignable_id');
            $table->string('assignable_type');
            $table->uuid('role_id')->index();
            $table->string('group');
            $table->boolean('leader');
            $table->json('forms')->nullable();
            $table->timestamps();
        });

        Schema::table('team_members', function($table)
        {
            $table->foreign('team_id')
                ->references('id')->on('teams')
                ->onDelete('cascade');
        });

        Schema::create('stats_teams', function(Blueprint $table)
        {
            $table->uuid('id')->primary();
            $table->uuid('team_id')->index();
            $table->json('totals');
            $table->date('captured_at');
            $table->timestamps();
        });

        Schema::table('stats_teams', function($table)
        {
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

        Schema::drop('teams');
        Schema::drop('team_members');
        Schema::drop('stats_teams');
    }
}
