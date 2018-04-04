<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePublicationIdOnPublishedStoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `published_stories` MODIFY `publication_id` CHAR(36) NULL;');

        Schema::table('published_stories', function (Blueprint $table) {
            $table->bigInteger('published_id')->unsigned()->nullable();
        });

        DB::table('published_stories')
            ->join('fundraisers', 'published_stories.publication_id', '=', 'fundraisers.uuid')
            ->update([
                'published_stories.published_id' => DB::raw('`fundraisers`.`id`')
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('published_stories', function (Blueprint $table) {
            $table->dropColumn('published_id');
        });
    }
}
