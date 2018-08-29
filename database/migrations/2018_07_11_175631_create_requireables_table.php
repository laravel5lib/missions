<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequireablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requireables', function (Blueprint $table) {
            $table->uuid('requirement_id')->index();
            $table->uuid('requireable_id')->index();
            $table->string('requireable_type');
            $table->string('status')->nullable();
            $table->timestamps();
        });

        Schema::table('requireables', function (Blueprint $table) {
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
        Schema::dropIfExists('requireables');
    }
}
