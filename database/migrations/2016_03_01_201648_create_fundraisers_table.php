<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFundraisersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fundraisers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 60);
            $table->integer('goal_amount')->default(0);
            $table->text('description')->nullable();
            $table->boolean('public')->default(false);
            $table->uuid('fundable_id')->index();
            $table->string('fundable_type');
            $table->uuid('sponsor_id')->index();
            $table->string('sponsor_type');
            $table->timestamp('expires_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('fundraisers');
    }
}
