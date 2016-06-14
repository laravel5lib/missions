<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicalReleasesTable extends Migration
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
            $table->json('conditions')->nullable(); // name, medication
            $table->json('allergies')->nullable(); // name, medication
            $table->boolean('is_risk')->default(false);
            $table->boolean('has_insurance')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('medical_releases');
    }
}
