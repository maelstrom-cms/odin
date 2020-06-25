<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingBodyToUptimeResponse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('uptime_scans', function (Blueprint $table) {
            $table->text('response_body')->after('response_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('uptime_scans', function (Blueprint $table) {
            $table->dropColumn('response_body');
        });
    }
}
