<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicalTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_releases', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->string('name');
            $table->string('ins_provider')->nullable();
            $table->string('ins_policy_no')->nullable();
            $table->json('emergency_contact')->nullable();
            $table->boolean('is_risk')->default(false);
            $table->timestamps();
        });

        Schema::table('medical_releases', function ($table) {
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });

        Schema::create('medical_conditions', function ($table) {
            $table->uuid('id')->primary();
            $table->uuid('medical_release_id')->index();
            $table->string('name');
            $table->boolean('medication');
            $table->boolean('diagnosed');
            $table->timestamps();
        });

        Schema::table('medical_conditions', function ($table) {
            $table->foreign('medical_release_id')
                ->references('id')->on('medical_releases')
                ->onDelete('cascade');
        });

        Schema::create('medical_allergies', function ($table) {
            $table->uuid('id')->primary();
            $table->uuid('medical_release_id')->index();
            $table->string('name');
            $table->boolean('medication');
            $table->boolean('diagnosed');
            $table->timestamps();
        });

        Schema::table('medical_allergies', function ($table) {
            $table->foreign('medical_release_id')
                ->references('id')->on('medical_releases')
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

        Schema::dropIfExists('medical_releases');
        Schema::dropIfExists('medical_conditions');
        Schema::dropIfExists('medical_allergies');
    }
}
