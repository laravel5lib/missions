<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('credentials', function(Blueprint $table) {
            $table->renameColumn('holder_id', 'user_id');
            $table->dropColumn('holder_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('credentials', function(Blueprint $table) {
            $table->renameColumn('user_id', 'holder_id');
            $table->string('holder_type')->default('users');
        });
    }
}
