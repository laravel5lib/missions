<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCostsAndPaymentsTables extends Migration
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

        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('cost_id')->index();
            $table->integer('amount_owed')->default(0);
            $table->integer('percent_owed')->default(0);
            $table->timestamp('due_at')->nullable();
            $table->integer('grace_period')->default(0);
            $table->boolean('upfront')->default(false);
        });

        Schema::table('payments', function($table)
        {
            $table->foreign('cost_id')
                ->references('id')->on('costs')
                ->onDelete('cascade');
        });

        Schema::create('dues', function(Blueprint $table) {
            $table->uuid('payment_id')->index();
            $table->uuid('payable_id')->index();
            $table->uuid('payable_type')->index();
            $table->integer('outstanding_balance')->default(0);
            $table->dateTime('due_at');
            $table->integer('grace_period');
        });

        Schema::table('dues', function($table)
        {
            $table->foreign('payment_id')
                ->references('id')->on('payments')
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
        Schema::disableForeignKeyConstraints();

        Schema::drop('costs');
        Schema::drop('payments');
        Schema::drop('dues');
    }
}
