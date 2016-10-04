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
        Schema::create('causes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('short_desc')->nullable();
            $table->uuid('upload_id')->nullable();
            $table->boolean('active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('short_desc')->nullable();
            $table->uuid('upload_id')->nullable();
            $table->boolean('active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('project_initiatives', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->uuid('cause_id')->index();
            $table->string('country_code');
            $table->timestamp('started_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('ended_at')->nullable();
            $table->boolean('active')->default(1);
            $table->uuid('rep_id')->index()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cause_id')
                ->references('id')->on('causes')
                ->onDelete('cascade');

            $table->foreign('rep_id')
                ->references('id')->on('users')
                ->onDelete('set null');
        });

        Schema::create('project_packages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('project_initiative_id')->index();
            $table->uuid('project_type_id')->index();
            $table->boolean('generate_dates')->default(0);

            $table->foreign('project_initiative_id')
                ->references('id')->on('project_initiatives')
                ->onDelete('cascade');

            $table->foreign('project_type_id')
                ->references('id')->on('project_types')
                ->onDelete('cascade');
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('project_package_id')->index();
            $table->uuid('rep_id')->index()->nullable();
            $table->uuid('sponsor_id')->index();
            $table->string('sponsor_type');
            $table->string('plaque_prefix')->nullable();
            $table->string('plaque_message')->nullable();
            $table->timestamp('funded_at')->nullable();
            $table->timestamp('launched_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('project_package_id')
                ->references('id')->on('project_packages')
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

        Schema::drop('causes');
        Schema::drop('project_types');
        Schema::drop('project_initiatives');
        Schema::drop('project_packages');
        Schema::drop('projects');
        Schema::drop('project_costs');
    }
}
