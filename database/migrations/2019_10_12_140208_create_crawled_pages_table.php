<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrawledPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crawled_pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('website_id');
            $table->text('url');
            $table->longText('messages')->nullable();
            $table->text('response')->nullable();
            $table->text('exception')->nullable();
            $table->timestamps();
        });

        Schema::table('websites', function (Blueprint $table) {
            $table->boolean('crawler_enabled')->default(0)->after('cron_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crawled_pages');

        Schema::table('websites', function (Blueprint $table) {
            $table->dropColumn('crawler_enabled');
        });
    }
}
