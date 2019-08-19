<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDnsScansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dns_scans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('website_id');
            $table->text('records')->nullable();
            $table->text('flat')->nullable();
            $table->text('diff')->nullable();
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
        Schema::dropIfExists('dns_scans');
    }
}
