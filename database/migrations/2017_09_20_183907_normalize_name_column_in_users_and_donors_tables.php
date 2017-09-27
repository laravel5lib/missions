<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NormalizeNameColumnInUsersAndDonorsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add the 'last_name' column and rename 'name' to 'first_name'
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'first_name');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('last_name', 60)->nullable();
        });

        Schema::table('donors', function (Blueprint $table) {
            $table->renameColumn('name', 'first_name');
        });

        Schema::table('donors', function (Blueprint $table) {
            $table->string('last_name', 60)->nullable();
        });

        if (env('DB_CONNECTION') === 'mysql') {
            // Extract the last word in the string value of the 'first_name' column
            // and use it as the string value for the 'last_name' column
            DB::statement('UPDATE users SET last_name = SUBSTRING_INDEX(first_name, " ", -1), first_name = RTRIM(REVERSE(SUBSTRING(REVERSE(first_name),LOCATE(" ",REVERSE(first_name)))))');

            DB::statement('UPDATE donors SET last_name = SUBSTRING_INDEX(first_name, " ", -1), first_name = RTRIM(REVERSE(SUBSTRING(REVERSE(first_name),LOCATE(" ",REVERSE(first_name)))))');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (env('DB_CONNECTION') === 'mysql') {
            // Combine the values for 'first_name' and 'last_name' columns
            // and use it as the value of 'first_name'
            DB::statement("UPDATE users SET first_name = CONCAT(first_name, ' ', last_name)");

            DB::statement("UPDATE donors SET first_name = CONCAT(first_name, ' ', last_name)");
        }

        // Drop the 'last_name' column and rename 'first_name' to 'name'
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_name');
            $table->renameColumn('first_name', 'name');
        });

        Schema::table('donors', function (Blueprint $table) {
            $table->dropColumn('last_name');
            $table->renameColumn('first_name', 'name');
        });
    }
}
