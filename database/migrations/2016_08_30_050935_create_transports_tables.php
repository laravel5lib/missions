<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransportsTables extends Migration
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

        Schema::table('transports', function($table)
        {
            $table->foreign('campaign_id')
                ->references('id')->on('campaigns')
                ->onDelete('cascade');
        });

        Schema::create('passengers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('reservation_id')->index();
            $table->uuid('transport_id')->index();
            $table->string('seat_no')->nullable();
            $table->timestamps();
        });

        Schema::table('passengers', function($table)
        {
            $table->foreign('reservation_id')
                ->references('id')->on('reservations')
                ->onDelete('cascade');

            $table->foreign('transport_id')
                ->references('id')->on('transports')
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

        Schema::drop('transports');
        Schema::drop('passengers');
    }
}
