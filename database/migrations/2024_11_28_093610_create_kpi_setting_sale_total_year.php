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
        Schema::create('kpi_setting_sale_total_year', function (Blueprint $table) {
            $table->id();
            $table->string('min')->nullable();
            $table->string('max')->nullable();
            $table->string('percentage')->nullable();
            $table->string('name')->nullable();
            $table->string('personal_points')->nullable();
            $table->unsignedBigInteger('kpi_setting_total_id')->nullable();
            $table->foreign('kpi_setting_total_id')->references('id')->on('kpi_setting_total')->onDelete('cascade');
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
        Schema::dropIfExists('kpi_setting_sale_total_year');
    }
};
