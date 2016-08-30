<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('country_code');
            $table->string('call_sign');
            $table->uuid('campaign_id')->index();
            $table->timestamps();
        });

        Schema::table('regions', function($table)
        {
            $table->foreign('campaign_id')
                ->references('id')->on('campaigns')
                ->onDelete('cascade');
        });

        Schema::create('stats_regions', function(Blueprint $table)
        {
            $table->uuid('id')->primary();
            $table->uuid('region_id')->index();
            $table->json('totals');
            $table->date('captured_at');
            $table->timestamps();
        });

        Schema::table('stats_regions', function($table)
        {
            $table->foreign('region_id')
                ->references('id')->on('regions')
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

        Schema::drop('regions');
        Schema::drop('stats_regions');
    }
}
