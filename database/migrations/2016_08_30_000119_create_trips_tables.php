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
            $table->json('todos')->nullable();
            $table->json('prospects')->nullable();
            $table->text('description');
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

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::drop('facilitators');
        Schema::drop('trips');
        Schema::dropIfExists('trip_interests');
    }
}
