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
        Schema::create('fp_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('fp_id');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->integer('qty')->default(1);
            $table->string('price_buy')->nullable();
            $table->string('price_sell')->nullable();
            $table->string('profit')->nullable();
            $table->string('total_buy')->nullable();
            $table->string('total_sell')->nullable();
            $table->string('file')->nullable();
            $table->string('file_url')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('fp_details');
    }
};
