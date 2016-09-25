<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinancialsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 60);
            $table->string('company', 60)->nullable();
            $table->string('email', 60)->nullable();
            $table->string('phone', 60)->nullable();
            $table->string('zip', 10)->nullable();
            $table->string('country_code')->nullable();
            $table->uuid('account_id')->nullable();
            $table->string('account_type')->nullable();
            $table->string('customer_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('funds', function(Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->integer('balance');
            $table->uuid('fundable_id');
            $table->uuid('fundable_type');
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('amount');
            $table->string('type');
            $table->json('payment')->nullable();
            $table->uuid('fund_id')->index();
            $table->uuid('donor_id')->nullable()->index();
            $table->string('description');
            $table->string('comment', 120)->nullable();
            $table->timestamps();
        });

        Schema::table('transactions', function($table)
        {
            $table->foreign('fund_id')
                ->references('id')->on('funds')
                ->onDelete('cascade');

            $table->foreign('donor_id')
                ->references('id')->on('donors')
                ->onDelete('cascade');
        });

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

        Schema::table('fundraisers', function($table)
        {
            $table->foreign('fund_id')
                ->references('id')->on('funds')
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

        Schema::drop('donors');
        Schema::drop('funds');
        Schema::drop('transactions');
        Schema::drop('fundraisers');
    }
}
