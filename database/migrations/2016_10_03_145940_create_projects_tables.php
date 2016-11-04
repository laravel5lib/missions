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

        Schema::create('project_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('project_cause_id');
            $table->string('name');
            $table->string('country_code');
            $table->string('short_desc')->nullable();
            $table->uuid('upload_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('project_type_id')->index();
            $table->uuid('rep_id')->index()->nullable();
            $table->uuid('sponsor_id')->index();
            $table->string('sponsor_type');
            $table->string('plaque_prefix')->nullable();
            $table->string('plaque_message')->nullable();
            $table->date('funded_at')->nullable();
            $table->date('launched_at')->nullable();
            $table->date('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('project_type_id')
                ->references('id')->on('project_types')
                ->onDelete('cascade');

            $table->foreign('rep_id')
                ->references('id')->on('users')
                ->onDelete('set null');
        });

        Schema::create('project_costs', function (Blueprint $table) {
            $table->uuid('cost_id')->index();
            $table->uuid('project_id')->index();
            $table->integer('quantity')->unsigned()->default(1);
            $table->timestamps();

            $table->foreign('cost_id')
                ->references('id')->on('costs')
                ->onDelete('cascade');

            $table->foreign('project_id')
                ->references('id')->on('projects')
                ->onDelete('cascade');

            $table->primary(['project_id', 'cost_id']);
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

        Schema::drop('project_causes');
        Schema::drop('project_types');
        Schema::drop('projects');
        Schema::drop('project_costs');
    }
}
