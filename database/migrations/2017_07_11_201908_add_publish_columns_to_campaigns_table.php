<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPublishColumnsToCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaigns', function ($table) {
            $table->boolean('publish_regions')->default(false);
            $table->boolean('publish_squads')->default(false);
            $table->boolean('publish_rooms')->default(false);
            $table->boolean('publish_transports')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaigns', function ($table) {
           $table->dropColumn('publish_regions');
           $table->dropColumn('publish_squads');
           $table->dropColumn('publish_rooms');
           $table->dropColumn('publish_transports');
        });
    }
}
