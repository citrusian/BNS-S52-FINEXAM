<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b_detail_transaksis', function (Blueprint $table) {
            $table->id();
            $table->integer('Transaksi_id')->unique();
            $table->string('Product_id');
            $table->string('Serial_no');
            $table->integer('Price');
            $table->integer('Discount');
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
        Schema::dropIfExists('b_detail_transaksis');
    }
};
