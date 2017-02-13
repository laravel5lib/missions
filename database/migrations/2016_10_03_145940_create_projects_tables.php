<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_causes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('short_desc')->nullable();
            $table->uuid('upload_id')->nullable();
            $table->json('countries');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_initiatives', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('project_cause_id');
            $table->string('type');
            $table->string('country_code');
            $table->string('short_desc')->nullable();
            $table->uuid('upload_id')->nullable();
            $table->timestamp('started_at');
            $table->timestamp('ended_at');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->uuid('project_initiative_id')->index();
            $table->uuid('rep_id')->index()->nullable();
            $table->uuid('sponsor_id')->index();
            $table->string('sponsor_type');
            $table->string('plaque_prefix')->nullable();
            $table->string('plaque_message')->nullable();
            $table->date('funded_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('project_initiative_id')
                ->references('id')->on('project_initiatives')
                ->onDelete('cascade');

            $table->foreign('rep_id')
                ->references('id')->on('users')
                ->onDelete('set null');
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

        Schema::dropIfExists('project_causes');
        Schema::dropIfExists('project_initiatives');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('project_costs');
        Schema::dropIfExists('project_deadlines');
    }
}
