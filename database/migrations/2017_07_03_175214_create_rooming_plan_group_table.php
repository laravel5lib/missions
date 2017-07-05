<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomingPlanGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooming_plan_group', function (Blueprint $table) {
            $table->uuid('rooming_plan_id');
            $table->uuid('group_id');
            $table->timestamps();
        });

        Schema::table('rooming_plans', function (Blueprint $table) {
            $table->string('group_id', 36)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooming_plan_group');

        Schema::table('rooming_plans', function (Blueprint $table) {
            $table->string('group_id', 36)->change();
        });
    }
}
