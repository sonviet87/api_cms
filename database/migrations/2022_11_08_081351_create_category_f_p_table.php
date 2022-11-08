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
        Schema::create('category_fp', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('fp_id');
            $table->integer('qty')->default(1);
            $table->string('price_buy')->nullable();
            $table->string('price_sell')->nullable();
            $table->string('profit')->nullable();
            $table->string('price_guest')->nullable();
            $table->string('price_bids')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
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
        Schema::dropIfExists('category_fp');
    }
};
