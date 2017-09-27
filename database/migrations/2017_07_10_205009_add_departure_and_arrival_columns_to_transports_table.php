<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDepartureAndArrivalColumnsToTransportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transports', function (Blueprint $table) {
            $table->dateTime('depart_at')->nullable();
            $table->uuid('departure_hub_id')->nullable();
            $table->dateTime('arrive_at')->nullable();
            $table->uuid('arrival_hub_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transports', function (Blueprint $table) {
            $table->dropColumn('depart_at');
            $table->dropColumn('departure_hub_id');
            $table->dropColumn('arrive_at');
            $table->dropColumn('arrival_hub_id');
        });
    }
}
