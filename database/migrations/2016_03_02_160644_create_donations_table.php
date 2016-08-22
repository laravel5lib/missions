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
            $table->string('currency', 3);
            $table->string('description', 120)->nullable();
            $table->string('message', 120)->nullable();
            $table->boolean('anonymous')->default(false);
            $table->uuid('donor_id')->index();
            $table->uuid('designation_id')->index();
            $table->string('designation_type');
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
