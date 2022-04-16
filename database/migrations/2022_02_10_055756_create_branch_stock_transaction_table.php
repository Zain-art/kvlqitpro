<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchStockTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_stock_transaction', function (Blueprint $table) {
            $table->id();
            $table->integer('branch_id');
            $table->binary('items_detail');
            $table->longText('note')->nullable();
            $table->string('voucher_number');
            $table->date('voucher_date');
            $table->text('net_qty');
            $table->integer('branch');
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
        Schema::dropIfExists('branch_stock_transaction');
    }
}
