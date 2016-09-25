<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccoladesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accolades', function (Blueprint $table) {
            $table->string('display_name');
            $table->string('name');
            $table->json('items')->nullabe();
            $table->uuid('recipient_id');
            $table->uuid('recipient_type');
            $table->timestamps();
            $table->softDeletes();
            $table->primary(['recipient_id', 'recipient_type', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('accolades');
    }
}
