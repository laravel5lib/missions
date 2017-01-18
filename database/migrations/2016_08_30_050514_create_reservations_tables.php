<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('given_names');
            $table->string('surname');
            $table->string('gender');
            $table->string('status');
            $table->string('shirt_size')->nullable();
            $table->date('birthday');
            $table->string('email')->nullable();
            $table->string('phone_one', 20)->nullable();
            $table->string('phone_two', 20)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('zip', 15)->nullable();
            $table->string('country_code', 2)->default('us');
            $table->string('desired_role', 4)->nullable();
            $table->uuid('user_id')->index();
            $table->uuid('trip_id')->index();
            $table->uuid('rep_id')->index()->nullable();
            $table->uuid('avatar_upload_id')->nullable();
            $table->integer('companion_limit')->unsigned()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('reservations', function($table)
        {
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('trip_id')
                ->references('id')->on('trips')
                ->onDelete('cascade');

            $table->foreign('rep_id')
                ->references('id')->on('users')
                ->onDelete('set null');

            $table->foreign('avatar_upload_id')
                ->references('id')->on('uploads')
                ->onDelete('set null');
        });

        Schema::create('reservation_requirements', function(Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('requirement_id')->index();
            $table->uuid('reservation_id')->index();
            $table->integer('grace_period')->default(0);
            $table->string('status');
            $table->string('document_type')->nullable();
            $table->uuid('document_id')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::table('reservation_requirements', function($table)
        {
            $table->foreign('requirement_id')
                ->references('id')->on('requirements')
                ->onDelete('cascade');

            $table->foreign('reservation_id')
                ->references('id')->on('reservations')
                ->onDelete('cascade');
        });

        Schema::create('reservation_costs', function(Blueprint $table) {
            $table->uuid('cost_id')->index();
            $table->uuid('reservation_id')->index();
            $table->boolean('locked')->default(false);
            $table->timestamps();
        });

        Schema::table('reservation_costs', function($table)
        {
            $table->foreign('cost_id')
                ->references('id')->on('costs')
                ->onDelete('cascade');

            $table->foreign('reservation_id')
                ->references('id')->on('reservations')
                ->onDelete('cascade');
        });

        Schema::create('reservation_deadlines', function (Blueprint $table) {
            $table->uuid('deadline_id')->index();
            $table->uuid('reservation_id')->index();
            $table->integer('grace_period')->default(0);
            $table->timestamps();
        });

        Schema::table('reservation_deadlines', function($table)
        {
            $table->foreign('deadline_id')
                ->references('id')->on('deadlines')
                ->onDelete('cascade');

            $table->foreign('reservation_id')
                ->references('id')->on('reservations')
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

        Schema::drop('reservations');
        Schema::drop('reservation_requirements');
        Schema::drop('reservation_costs');
        Schema::drop('reservation_deadlines');
    }
}
