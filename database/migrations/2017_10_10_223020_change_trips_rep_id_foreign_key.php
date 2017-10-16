<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTripsRepIdForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('trips', function (Blueprint $table) {
            $table->dropForeign(['rep_id']);

            $table->foreign('rep_id')
                ->references('id')->on('representatives')
                ->onDelete('set null');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        
        Schema::table('trips', function (Blueprint $table) {
            $table->dropForeign(['rep_id']);

            $table->foreign('rep_id')
                ->references('id')->on('users')
                ->onDelete('set null');
        });

        Schema::enableForeignKeyConstraints();
    }
}
