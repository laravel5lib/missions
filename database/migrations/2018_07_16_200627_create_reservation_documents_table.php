<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation_documents', function (Blueprint $table) {
            $table->uuid('reservation_id')->index();
            $table->uuid('documentable_id')->index();
            $table->string('documentable_type');
            $table->timestamps();
        });

        Schema::table('reservation_documents', function (Blueprint $table) {
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
        Schema::dropIfExists('reservation_documents');
    }
}
