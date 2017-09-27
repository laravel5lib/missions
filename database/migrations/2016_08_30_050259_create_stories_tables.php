<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoriesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('content');
            $table->uuid('author_id');
            $table->string('author_type');
            $table->boolean('featured')->default(false);
            $table->timestamps();
        });

        Schema::create('published_stories', function (Blueprint $table) {
            $table->uuid('story_id');
            $table->uuid('publication_id');
            $table->string('publication_type');
            $table->timestamp('published_at');
        });

        Schema::table('published_stories', function ($table) {
            $table->foreign('story_id')
                ->references('id')->on('stories')
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
        Schema::disableForeignKeyConstraints();

        Schema::drop('stories');
        Schema::drop('published_stories');
    }
}
