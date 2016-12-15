<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRecordsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->string('given_names');
            $table->string('surname');
            $table->string('number');
            $table->string('birth_country', 5);
            $table->string('citizenship', 5);
            $table->uuid('upload_id')->nullable();
            $table->date('issued_at');
            $table->date('expires_at');
            $table->timestamps();
        });

        Schema::table('passports', function($table)
        {
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('upload_id')
                ->references('id')->on('uploads')
                ->onDelete('set null');
        });

        Schema::create('visas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->string('given_names');
            $table->string('surname');
            $table->string('number');
            $table->string('country_code');
            $table->uuid('upload_id')->nullable();
            $table->date('issued_at');
            $table->date('expires_at');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('visas', function($table)
        {
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('upload_id')
                ->references('id')->on('uploads')
                ->onDelete('set null');
        });

        Schema::create('referrals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->string('name');
            $table->string('type');
            $table->string('referral_name');
            $table->string('referral_email');
            $table->string('referral_phone');
            $table->string('status');
            $table->json('response')->nullable();
            $table->timestamp('sent_at');
            $table->timestamps();
        });

        Schema::table('referrals', function($table)
        {
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });

        Schema::create('contacts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index()->nullable();
            $table->string('suffix');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('relationship')->default('other');
            $table->string('email')->nullable();
            $table->string('phone_one')->nullable();
            $table->string('phone_two')->nullable();
            $table->string('address_street')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_state')->nullable();
            $table->string('address_zip')->nullable();
            $table->string('country_code', 2)->nullable();
            $table->boolean('emergency')->default(false);
            $table->timestamps();
        });

        Schema::table('contacts', function($table)
        {
            $table->foreign('user_id')
                ->references('id')->on('users')
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

        Schema::dropIfExists('passports');
        Schema::dropIfExists('visas');
        Schema::dropIfExists('referrals');
        Schema::dropIfExists('contacts');
    }
}
