<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('group_id')->index();
            $table->uuid('campaign_id')->index()->nullable();
            $table->uuid('rep_id')->index();
            $table->integer('spots')->default(0);
            $table->integer('companion_limit')->default(0);
            $table->string('country_code');
            $table->string('type');
            $table->string('difficulty');
            $table->date('started_at');
            $table->date('ended_at');
            $table->json('todos')->nullable();
            $table->json('prospects')->nullable();
            $table->text('description');
            $table->timestamp('published_at')->nullable();
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
        Schema::drop('trips');
    }
}
