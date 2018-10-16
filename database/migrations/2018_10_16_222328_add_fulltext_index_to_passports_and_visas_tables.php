<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFulltextIndexToPassportsAndVisasTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE passports ADD FULLTEXT full(given_names, surname)');
        DB::statement('ALTER TABLE visas ADD FULLTEXT full(given_names, surname)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE passports DROP INDEX full');
        DB::statement('ALTER TABLE visas DROP INDEX full');
    }
}
