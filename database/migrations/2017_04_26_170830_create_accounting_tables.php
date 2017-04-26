<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountingTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_classes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('accounting_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('funds', function (Blueprint $table) {
            $table->uuid('class_id')->after('class')->nullabe();
            $table->uuid('item_id')->after('item')->nullable();

            $table->foreign('class_id')
                ->references('id')->on('accounting_classes')
                ->onDelete('set null');

            $table->foreign('item_id')
                ->references('id')->on('accounting_items')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('funds', function (Blueprint $table) {
            $table->dropForeign(['class_id', 'item_id']);
            $table->dropColumn(['class_id', 'item_id']);
        });
        Schema::drop('accounting_classes');
        Schema::drop('accounting_items');
    }
}
