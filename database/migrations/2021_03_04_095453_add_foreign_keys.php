<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('open_graph_scans', function (Blueprint $table) {
            $table->bigInteger('website_id')->unsigned()->index()->change();
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
        });

        Schema::table('robot_scans', function (Blueprint $table) {
            $table->bigInteger('website_id')->unsigned()->index()->change();
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
        });

        Schema::table('dns_scans', function (Blueprint $table) {
            $table->bigInteger('website_id')->unsigned()->index()->change();
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
        });

        Schema::table('visual_diffs', function (Blueprint $table) {
            $table->bigInteger('website_id')->unsigned()->index()->change();
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
        });

        Schema::table('cron_pings', function (Blueprint $table) {
            $table->bigInteger('website_id')->unsigned()->index()->change();
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
        });

        Schema::table('uptime_scans', function (Blueprint $table) {
            $table->bigInteger('website_id')->unsigned()->index()->change();
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
        });

        Schema::table('certificate_scans', function (Blueprint $table) {
            $table->bigInteger('website_id')->unsigned()->index()->change();
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
        });

        Schema::table('crawled_pages', function (Blueprint $table) {
            $table->bigInteger('website_id')->unsigned()->index()->change();
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('open_graph_scans', function (Blueprint $table) {
            $table->integer('website_id')->change();
            $table->dropForeign('website_id');
        });

        Schema::table('robot_scans', function (Blueprint $table) {
            $table->integer('website_id')->change();
            $table->dropForeign('website_id');
        });

        Schema::table('dns_scans', function (Blueprint $table) {
            $table->integer('website_id')->change();
            $table->dropForeign('website_id');
        });

        Schema::table('visual_diffs', function (Blueprint $table) {
            $table->integer('website_id')->change();
            $table->dropForeign('website_id');
        });

        Schema::table('cron_pings', function (Blueprint $table) {
            $table->integer('website_id')->change();
            $table->dropForeign('website_id');
        });

        Schema::table('uptime_scans', function (Blueprint $table) {
            $table->integer('website_id')->change();
            $table->dropForeign('website_id');
        });

        Schema::table('certificate_scans', function (Blueprint $table) {
            $table->integer('website_id')->change();
            $table->dropForeign('website_id');
        });

        Schema::table('crawled_pages', function (Blueprint $table) {
            $table->integer('website_id')->change();
            $table->dropForeign('website_id');
        });
    }
}
