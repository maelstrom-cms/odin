<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingFoundOnToCrawler extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crawled_pages', function (Blueprint $table) {
            $table->longText('found_on')->nullable()->after('exception');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crawled_pages', function (Blueprint $table) {
            $table->dropColumn('found_on');
        });
    }
}
