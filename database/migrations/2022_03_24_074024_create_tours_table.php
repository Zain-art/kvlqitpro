<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->string('tour_name');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('number_of_tourists')->nullable();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->binary('expense_details')->nullable();
            $table->integer('is_tour_open')->default(1);
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('tours');
    }
}
