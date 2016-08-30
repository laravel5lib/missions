<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInteractionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('decisions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('team_member_id')->index();
            $table->uuid('region_id')->index();
            $table->string('name');
            $table->string('gender');
            $table->string('age_group');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->boolean('decision')->default(false);
            $table->timestamps();
        });

        Schema::table('decisions', function($table)
        {
            $table->foreign('region_id')
                ->references('id')->on('regions')
                ->onDelete('cascade');

            $table->foreign('team_member_id')
                ->references('id')->on('team_members')
                ->onDelete('cascade');
        });

        Schema::create('exams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('team_member_id')->index();
            $table->uuid('region_id')->index();
            $table->integer('party_size')->default(1);
            $table->string('name');
            $table->string('gender');
            $table->string('age_group');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->boolean('decision')->default(false);
            $table->json('treatments')->nullable();
            $table->timestamps();
        });

        Schema::table('exams', function($table)
        {
            $table->foreign('region_id')
                ->references('id')->on('regions')
                ->onDelete('cascade');

            $table->foreign('team_member_id')
                ->references('id')->on('team_members')
                ->onDelete('cascade');
        });

        Schema::create('sites', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('team_member_id')->index();
            $table->uuid('region_id')->index();
            $table->string('call_sign');
            $table->string('site_type');
            $table->integer('total_reached');
            $table->integer('total_decisions');
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->timestamps();
        });

        Schema::table('sites', function($table)
        {
            $table->foreign('region_id')
                ->references('id')->on('regions')
                ->onDelete('cascade');

            $table->foreign('team_member_id')
                ->references('id')->on('team_members')
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

        Schema::drop('decisions');
        Schema::drop('exams');
        Schema::drop('sites');
    }
}
