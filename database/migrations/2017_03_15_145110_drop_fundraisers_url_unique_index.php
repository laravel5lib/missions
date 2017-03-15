<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropFundraisersUrlUniqueIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fundraisers', function(Blueprint $table)
        {
            $table->dropUnique('fundraisers_url_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fundraisers', function(Blueprint $table)
        {
            $table->unique('url');
        });
    }
}
