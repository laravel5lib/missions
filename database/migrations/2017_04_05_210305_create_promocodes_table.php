<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromocodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocodes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('promotional_id')->index();
            $table->string('code', 32);
            $table->uuid('rewardable_id')->nullable();
            $table->string('rewardable_type')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('promocodes', function (Blueprint $table) {
            $table->foreign('promotional_id')
                ->references('id')->on('promotionals')
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
        Schema::drop('promocodes');
    }
}
