<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientLicenseValidationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_license_validation', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('phone_number');
            $table->text('address');
            $table->integer('pos_id');
            $table->string('license_key')->nullable();
            $table->date('last_validation_date')->nullable();
            $table->date('next_validation_date')->nullable();
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
        Schema::dropIfExists('client_license_validation');
    }
}
