<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requirements', function(Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('item');
            $table->string('item_type')->nullable();
            $table->timestamp('due_at');
            $table->integer('grace_period');
            $table->boolean('enforced')->default(false);
            $table->uuid('requirable_id')->index();
            $table->string('requirable_type');
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
        Schema::drop('requirements');
    }
}
