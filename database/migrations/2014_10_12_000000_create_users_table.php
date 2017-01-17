<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 60);
            $table->string('email', 60);
            $table->string('alt_email', 60)->nullable();
            $table->string('password', 60);
            $table->string('gender', 10)->nullable();
            $table->string('status', 10)->nullable();
            $table->date('birthday')->nullable();
            $table->string('phone_one', 20)->nullable();
            $table->string('phone_two', 20)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('zip', 15)->nullable();
            $table->string('country_code', 2)->default('us');
            $table->string('timezone', 20)->default('US/Eastern');
            $table->string('bio', 120)->nullable();
            $table->boolean('public')->default(false);
            $table->string('stripe_id')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_last_four')->nullable();
            $table->uuid('avatar_upload_id')->nullable();
            $table->uuid('banner_upload_id')->nullable();
            $table->rememberToken();
            $table->softDeletes();
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
        Schema::drop('users');
    }
}
