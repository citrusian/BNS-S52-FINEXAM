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
        Schema::create('b_transaksis', function (Blueprint $table) {
            $table->id();
            $table->date('Tanggal');
            $table->integer('No_Trans')->unique();
            $table->string('Customer / Vendor');
            $table->string('Trans_Type');
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
        Schema::dropIfExists('b_transaksis');
    }
};
