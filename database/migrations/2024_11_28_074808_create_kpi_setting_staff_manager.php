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
        Schema::create('kpi_setting_staff_manager', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('percent')->nullable();
            $table->unsignedBigInteger('kpi_setting_sale_id')->nullable();
            $table->string('type')->default('1months')->nullable();
            $table->string('kpi_type')->default('sale')->nullable();
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
        Schema::dropIfExists('kpi_setting_staff_manager');
    }
};
