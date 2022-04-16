<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_license_validation', function (Blueprint $table) {
            $table->string('ntn_no');
            $table->string('irs_password');
            $table->string('sales_tax_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_license_validation', function (Blueprint $table) {
            //
        });
    }
}
