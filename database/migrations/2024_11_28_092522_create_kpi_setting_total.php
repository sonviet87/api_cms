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
        Schema::create('kpi_setting_total', function (Blueprint $table) {
            $table->id();
            $table->string('min')->nullable();
            $table->string('max')->nullable();
            $table->string('percentage')->nullable();
            $table->string('name')->nullable();
            $table->string('points')->nullable();
            $table->string('parent_id')->default(0)->nullable();
            $table->string('bonus')->nullable();
            $table->string('type')->nullable();//1months,13months,12months
            $table->string('type_kpi')->default('normal')->nullable();//normal or year
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
        Schema::dropIfExists('kpi_setting_total');
    }
};
