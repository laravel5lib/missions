<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('costs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 60);
            $table->integer('amount')->default(0);
            $table->string('description')->nullable();
            $table->string('type')->default('static');
            $table->timestamp('active_at');
            $table->uuid('cost_assignable_id')->index();
            $table->string('cost_assignable_type')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('costs');
    }
}
