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
        Schema::create('kpi_setting_supplier', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('year')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->json('new_supplier_conditions')->nullable();
            $table->string('new_supplier_target')->nullable();
            $table->json('old_supplier_conditions')->nullable();
            $table->string('old_supplier_target')->nullable();
            $table->string('new_supplier_percent')->nullable();
            $table->string('old_supplier_percent')->nullable();
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
        Schema::dropIfExists('kpi_setting_supplier');
    }
};
