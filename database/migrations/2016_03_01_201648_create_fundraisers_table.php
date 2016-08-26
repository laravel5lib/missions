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
            $table->string('url')->unique();
            $table->string('type')->default('general');
            $table->integer('goal_amount')->default(0);
            $table->text('description')->nullable();
            $table->boolean('public')->default(false);
            $table->uuid('fund_id')->index();
            $table->uuid('sponsor_id')->index();
            $table->string('sponsor_type');
            $table->uuid('banner_upload_id')->nullable();
            $table->timestamp('started_at');
            $table->timestamp('ended_at');
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
