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
            $table->string('type');
            $table->string('vessel_no');
            $table->string('name');
            $table->string('call_sign')->nullable();
            $table->boolean('domestic')->default(0);
            $table->integer('capacity')->unsigned();
            $table->uuid('campaign_id')->index();
            $table->timestamp('departs_at')->nullable();
            $table->timestamp('arrives_at')->nullable();
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
        Schema::drop('transports');
    }
}
