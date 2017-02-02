<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('group_id')->index();
            $table->uuid('campaign_id')->index();
            $table->integer('spots')->default(0);
            $table->integer('companion_limit')->default(0);
            $table->string('country_code');
            $table->string('type');
            $table->string('difficulty');
            $table->date('started_at');
            $table->date('ended_at');
            $table->uuid('rep_id')->nullable()->index();
            $table->json('todos')->nullable();
            $table->json('prospects')->nullable();
            $table->json('team_roles')->nullable();
            $table->text('description');
            $table->boolean('public')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('closed_at');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('trips', function($table)
        {
            $table->foreign('group_id')
                ->references('id')->on('groups')
                ->onDelete('cascade');

            $table->foreign('campaign_id')
                ->references('id')->on('campaigns')
                ->onDelete('cascade');
        });

        Schema::create('facilitators', function (Blueprint $table) {
            $table->uuid('trip_id')->index();
            $table->uuid('user_id')->index();
            $table->json('permissions')->nullable();
            $table->timestamps();
        });

        Schema::table('facilitators', function($table)
        {
            $table->foreign('trip_id')
                ->references('id')->on('trips')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });

        Schema::create('trip_interests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('trip_id')->index();
            $table->string('status')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->json('communication_preferences')->nullable();
            $table->timestamps();
        });

        Schema::table('trip_interests', function($table)
        {
            $table->foreign('trip_id')
                ->references('id')->on('trips')
                ->onDelete('cascade');
        });

        Schema::create('prospects', function(Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
        });

        Schema::create('trip_prospect', function(Blueprint $table) {
            $table->uuid('trip_id');
            $table->uuid('prospect_id');
        });

        Schema::table('trip_prospect', function($table)
        {
            $table->foreign('trip_id')
                  ->references('id')->on('trips')
                  ->onDelete('cascade');

            $table->foreign('prospect_id')
                  ->references('id')->on('prospects')
                  ->onDelete('cascade');
        });

        Schema::create('assignments', function(Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
        });

        Schema::create('trip_assignment', function(Blueprint $table) {
            $table->uuid('trip_id');
            $table->uuid('assignment_id');
        });

        Schema::table('trip_assignment', function($table)
        {
            $table->foreign('trip_id')
                  ->references('id')->on('trips')
                  ->onDelete('cascade');

            $table->foreign('assignment_id')
                  ->references('id')->on('assignments')
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

        Schema::dropIfExists('facilitators');
        Schema::dropIfExists('trips');
        Schema::dropIfExists('trip_interests');
        Schema::dropIfExists('prospects');
        Schema::dropIfExists('trip_prospect');
        Schema::dropIfExists('assignments');
        Schema::dropIfExists('trip_assignment');
    }
}
