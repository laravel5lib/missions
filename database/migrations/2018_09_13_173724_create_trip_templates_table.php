<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_templates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->uuid('campaign_id')->index();
            $table->integer('spots')->default(0);
            $table->integer('companion_limit')->default(0);
            $table->string('country_code');
            $table->string('type');
            $table->string('difficulty');
            $table->date('started_at');
            $table->date('ended_at');
            $table->json('todos')->nullable();
            $table->json('prospects')->nullable();
            $table->json('team_roles')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('closed_at')->nullable();
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
        Schema::dropIfExists('trip_templates');
    }
}
