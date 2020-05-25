<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PreventOverlappingJobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->boolean('in_queue_uptime')->default(0)->after('crawler_enabled');
            $table->boolean('in_queue_dns')->default(0)->after('crawler_enabled');
            $table->boolean('in_queue_crawler')->default(0)->after('crawler_enabled');
            $table->boolean('in_queue_ssl')->default(0)->after('crawler_enabled');
            $table->boolean('in_queue_robots')->default(0)->after('crawler_enabled');
            $table->boolean('in_queue_og')->default(0)->after('crawler_enabled');

            $table->index('in_queue_uptime');
            $table->index('in_queue_dns');
            $table->index('in_queue_crawler');
            $table->index('in_queue_ssl');
            $table->index('in_queue_robots');
            $table->index('in_queue_og');

            $table->index('ssl_enabled');
            $table->index('uptime_keyword');
            $table->index('robots_enabled');
            $table->index('dns_enabled');
            $table->index('cron_enabled');
            $table->index('crawler_enabled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->dropColumn('in_queue_uptime');
            $table->dropColumn('in_queue_dns');
            $table->dropColumn('in_queue_crawler');
            $table->dropColumn('in_queue_ssl');
            $table->dropColumn('in_queue_robots');
            $table->dropColumn('in_queue_og');
        });
    }
}
