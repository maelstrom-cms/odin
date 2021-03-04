<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntermediateCertificateScansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intermediate_certificate_scans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('certificate_scan_id');
            $table->foreign('certificate_scan_id')->references('id')->on('certificate_scans')->onDelete('cascade');
            $table->string('issuer');
            $table->string('common_name');
            $table->dateTime('valid_from');
            $table->dateTime('valid_to');
            $table->boolean('was_valid');
            $table->boolean('did_expire');
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
        Schema::dropIfExists('intermediate_certificate_scans');
    }
}
