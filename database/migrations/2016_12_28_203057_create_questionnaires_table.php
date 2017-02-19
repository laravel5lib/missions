<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaires', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('reservation_id');
            $table->string('type');
            $table->json('content');
            $table->timestamps();
        });

        Schema::table('questionnaires', function($table)
        {
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
        Schema::dropIfExists('questionnaires');
    }
}
