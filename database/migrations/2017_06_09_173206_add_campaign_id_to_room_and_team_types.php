<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCampaignIdToRoomAndTeamTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->uuid('campaign_id')->nullable()->after('id');
        });

        Schema::table('team_types', function (Blueprint $table) {
            $table->uuid('campaign_id')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->dropColumn('campaign_id');
        });

        Schema::table('team_types', function (Blueprint $table) {
            $table->dropColumn('campaign_id');
        });
    }
}
