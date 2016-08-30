<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTagsTable extends Migration {

	public function up()
	{
		Schema::create('tagging_tags', function(Blueprint $table) {
			$table->increments('id');
			$table->string('slug', 255)->index();
			$table->string('name', 255);
			$table->boolean('suggest')->default(false);
			$table->integer('count')->unsigned()->default(0); // count of how many times this tag was used
		});

        Schema::create('tagging_tagged', function(Blueprint $table) {
            $table->increments('id');
            if(config('tagging.primary_keys_type') == 'string') {
                $table->string('taggable_id', 36)->index();
            } else {
                $table->integer('taggable_id')->unsigned()->index();
            }
            $table->string('taggable_type', 255)->index();
            $table->string('tag_name', 255);
            $table->string('tag_slug', 255)->index();
        });
	}

	public function down()
	{
        Schema::drop('tagging_tagged');
		Schema::drop('tagging_tags');
	}
}
