<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_group', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->uuid('group_id')->index();
            $table->uuid('campaign_id')->index();
            $table->integer('status_id')->index();
            $table->json('meta')->nullable();
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
        Schema::dropIfExists('campaign_group');
    }
}
