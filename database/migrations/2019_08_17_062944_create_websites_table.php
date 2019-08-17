<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebsitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('websites', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('url');
            $table->bigInteger('user_id');
            $table->dateTime('last_checked')->nullable();
            $table->boolean('ssl_enabled')->default(0);
            $table->boolean('uptime_enabled')->default(0);
            $table->boolean('robots_enabled')->default(0);
            $table->boolean('dns_enabled')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('websites');
    }
}
