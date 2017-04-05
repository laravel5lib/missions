<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('campaign_id')->index();
            $table->string('type');
            $table->string('vessel_no', 60);
            $table->string('name');
            $table->string('call_sign')->nullable();
            $table->integer('capacity')->nullable();
            $table->boolean('domestic')->default(false);
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
        Schema::drop('transports');
    }
}
