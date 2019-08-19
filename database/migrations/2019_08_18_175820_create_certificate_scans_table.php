<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificateScansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificate_scans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('website_id');
            $table->string('issuer');
            $table->string('domain');
            $table->text('additional_domains');
            $table->dateTime('valid_from');
            $table->dateTime('valid_to');
            $table->boolean('was_valid');
            $table->boolean('did_expire');
            $table->string('grade');
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
        Schema::dropIfExists('certificate_scans');
    }
}
