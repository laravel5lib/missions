<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dues', function(Blueprint $table) {
            $table->uuid('payment_id')->index();
            $table->uuid('payable_id')->index();
            $table->uuid('payable_type')->index();
            $table->integer('outstanding_balance')->default(0);
            $table->dateTime('due_at');
            $table->integer('grace_period');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dues');
    }
}
