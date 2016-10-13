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
            $table->string('name');
            $table->string('short_desc')->nullable();
            $table->string('document_type');
            $table->timestamp('due_at');
            $table->integer('grace_period');
            $table->uuid('requester_id')->index();
            $table->string('requester_type');
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
        Schema::drop('requirements');
    }
}
