<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAutoIncrementingIdsToFundraisersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fundraisers', function (Blueprint $table) {
            $table->dropPrimary(['id']);
            $table->renameColumn('id', 'uuid');
        });

        Schema::table('fundraisers', function (Blueprint $table) {
            $table->bigIncrements('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fundraisers', function (Blueprint $table) {
            $table->dropColumn('id');
        });

        Schema::table('fundraisers', function (Blueprint $table) {
            $table->renameColumn('uuid', 'id');
            $table->primary('id');
        });
    }
}
