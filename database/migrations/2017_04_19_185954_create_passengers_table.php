<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePassengersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passengers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('transport_id')->index();
            $table->uuid('reservation_id')->index();
            $table->string('seat_no');
            $table->timestamps();
        });

        Schema::table('passengers', function (Blueprint $table) {
            $table->foreign('transport_id')
                ->references('id')->on('transports')
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
        Schema::drop('passengers');
    }
}
