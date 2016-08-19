<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDonorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email', 60)->nullable();
            $table->string('phone', 60)->nullable();
            $table->string('name', 60);
            $table->string('company', 60)->nullable();
            $table->string('address_one', 100)->nullable();
            $table->string('address_two', 100)->nullable();
            $table->string('city', 60)->nullable();
            $table->string('state', 60)->nullable();
            $table->string('zip', 10)->nullable();
            $table->string('country_code', 2)->nullable();
            $table->uuid('account_holder_id')->nullable();
            $table->string('account_holder_type')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('donors');
    }
}
