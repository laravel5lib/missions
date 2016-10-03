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

        Schema::create('project_enhancements', function (Blueprint $table) {
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
            $table->integer('amount')->unsigned()->default(0);
            $table->string('currency_code')->default('USD');
            $table->boolean('generate_dates')->default(0);

            $table->foreign('project_initiative_id')
                ->references('id')->on('project_initiatives')
                ->onDelete('cascade');

            $table->foreign('project_type_id')
                ->references('id')->on('project_types')
                ->onDelete('cascade');
        });

        Schema::create('project_package_enhancements', function (Blueprint $table) {
            $table->uuid('project_package_id')->index();
            $table->uuid('project_enhancement_id')->index();
            $table->integer('amount')->unsigned()->default(0);
            $table->string('currency_code')->default('USD');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('project_package_id')
                ->references('id')->on('project_packages')
                ->onDelete('cascade');

            $table->foreign('project_enhancement_id')
                ->references('id')->on('project_enhancements')
                ->onDelete('cascade');

            $table->primary(['project_package_id', 'project_enhancement_id'], 'project_package_enhancement_primary');

        });

        Schema::create('project_package_dates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('project_package_id')->index();
            $table->timestamp('launched_at');
            $table->timestamp('half_due_at');
            $table->timestamp('full_due_at');
            $table->timestamp('latest_start_at');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('project_package_id')
                ->references('id')->on('project_packages')
                ->onDelete('cascade');

        });

        Schema::create('project_package_date_generators', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('project_package_id')->index();
            $table->integer('new_launch_after_days');
            $table->integer('halfdue_from_launch_days_out');
            $table->integer('fulldue_from_launch_days_out');
            $table->integer('lateststart_from_launch_days_out');
            $table->integer('available_cycles');

            $table->foreign('project_package_id')
                ->references('id')->on('project_packages')
                ->onDelete('cascade');
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('project_package_id')->index();
            $table->uuid('project_package_date_id')->index();
            $table->uuid('rep_id')->index()->nullable();
            $table->uuid('sponsor_id')->index();
            $table->string('sponsor_type');
            $table->integer('goal_amount')->default(0);
            $table->string('currency_code')->default('USD');
            $table->integer('plaque_prefix_id')->unsigned()->index()->nullable();
            $table->string('plaque_message')->nullable();
            $table->string('plaque_img_src')->nullable();
            $table->boolean('allow_collaboration')->default(true);
            $table->timestamp('funded_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('deadline_extended_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('project_package_id')
                ->references('id')->on('project_packages')
                ->onDelete('cascade');

            $table->foreign('project_package_date_id')
                ->references('id')->on('project_package_dates')
                ->onDelete('cascade');

            $table->foreign('rep_id')
                ->references('id')->on('users')
                ->onDelete('set null');
        });

        Schema::create('project_additions', function (Blueprint $table) {
            $table->uuid('project_id')->index();
            $table->uuid('project_enhancement_id')->index();
            $table->integer('quantity')->unsigned()->default(1);
            $table->integer('amount')->unsigned()->default(0);
            $table->string('currency_code')->default('USD');

            $table->primary(['project_id', 'project_enhancement_id'], 'project_addition_primary');
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
        Schema::drop('project_enhancements');
        Schema::drop('project_initiatives');
        Schema::drop('project_packages');
        Schema::drop('project_package_enhancements');
        Schema::drop('project_package_dates');
        Schema::drop('project_package_date_generators');
        Schema::drop('projects');
        Schema::drop('project_additions');
    }
}
