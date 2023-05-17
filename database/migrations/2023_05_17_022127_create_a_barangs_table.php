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
        Schema::create('a_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('Product_Name');
            $table->string('Brand');
            $table->unsignedBigInteger('Price');
            $table->string('Model_No')->unique();
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
        Schema::dropIfExists('a_barangs');
    }
};
