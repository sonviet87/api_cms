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
        Schema::create('kpi_setting_sale_item', function (Blueprint $table) {
            $table->id();
            $table->string('min')->nullable();
            $table->string('max')->nullable();
            $table->string('percentage')->nullable();
            $table->string('point')->nullable();
            $table->string('type')->nullable();
            $table->string('type_kpi')->default('sale');
            $table->unsignedBigInteger('kpi_setting_sale_id')->nullable();
            $table->foreign('kpi_setting_sale_id')->references('id')->on('kpi_setting_sale')->onDelete('cascade');
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
        Schema::dropIfExists('kpi_setting_sale_item');
    }
};
