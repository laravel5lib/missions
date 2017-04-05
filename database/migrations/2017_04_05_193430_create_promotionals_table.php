<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotionals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->integer('reward')->default(0);
            $table->uuid('promoteable_id')->nullable();
            $table->string('promoteable_type')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('promotionals');
    }
}
