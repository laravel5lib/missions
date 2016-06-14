<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDonationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('amount');
            $table->string('description', 120)->nullable();
            $table->string('message', 120)->nullable();
            $table->boolean('anonymous')->default(false);
            $table->string('email', 60);
            $table->string('phone', 60)->nullable();
            $table->string('name', 60);
            $table->string('company_name', 60)->nullable();
            $table->string('address_street', 100)->nullable();
            $table->string('address_city', 60)->nullable();
            $table->string('address_state', 60)->nullable();
            $table->string('address_zip', 10)->nullable();
            $table->string('address_country_code', 2)->nullable();
            $table->uuid('donor_id')->index();
            $table->string('donor_type');
            $table->uuid('designation_id')->index();
            $table->string('designation_type');
            $table->uuid('payment_id')->index();
            $table->string('payment_type');
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
        Schema::drop('donations');
    }
}
