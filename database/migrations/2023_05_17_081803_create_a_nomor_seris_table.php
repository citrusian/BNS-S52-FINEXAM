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
        Schema::create('a_nomor_seris', function (Blueprint $table) {
            $table->id();
            $table->string('Product_id')->unique();
            $table->string('Serial_no')->unique();
            $table->integer('Price');
            $table->date('Prod_date');

            //Warranty can be null if items not sold
            $table->date('Warranty_Start')->nullable();
            $table->date('Warranty_Duration')->nullable();

            $table->integer('Used');
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
        Schema::dropIfExists('a_nomor_seris');
    }
};
