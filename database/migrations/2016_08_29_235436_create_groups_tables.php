<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('type')->default('church');
            $table->string('description')->nullable();
            $table->boolean('public')->default(false);
            $table->string('timezone')->default('US/Eastern');
            $table->string('address_one')->nullable();
            $table->string('address_two')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('country_code')->default('us');
            $table->string('phone_one')->nullable();
            $table->string('phone_two')->nullable();
            $table->string('email')->nullable();
            $table->string('status');
            $table->string('stripe_id')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_last_four')->nullable();
            $table->uuid('avatar_upload_id')->nullable();
            $table->uuid('banner_upload_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('managers', function (Blueprint $table) {
            $table->uuid('group_id')->index();
            $table->uuid('user_id')->index();
            $table->timestamps();
        });

        Schema::table('managers', function ($table) {
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('group_id')
                ->references('id')->on('groups')
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

        Schema::drop('managers');
        Schema::drop('groups');
    }
}
