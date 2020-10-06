<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingVisualDiffOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->boolean('visual_diff_enabled')->default(0)->after('crawler_enabled');
            $table->boolean('in_queue_visual_diff')->default(0)->after('in_queue_uptime');
            $table->longText('visual_diff_urls')->nullable()->after('crawler_enabled');
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
            $table->dropColumn('visual_diff_enabled');
            $table->dropColumn('visual_diff_urls');
//            $table->dropColumn('in_queue_visual_diff');
        });
    }
}
