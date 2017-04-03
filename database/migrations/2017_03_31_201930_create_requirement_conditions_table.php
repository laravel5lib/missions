<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequirementConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requirement_conditions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('requirement_id')->index();
            $table->string('type');
            $table->string('condition');
            $table->json('applies_to');
            $table->timestamps();
        });

        Schema::table('requirement_conditions', function (Blueprint $table) {
            $table->foreign('requirement_id')
                ->references('id')->on('requirements')
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
        Schema::drop('requirement_conditions');
    }
}
