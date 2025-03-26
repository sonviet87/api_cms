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
        Schema::create('kpi_member_groups', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->string('profit_months')->nullable();
            $table->string('profit_3_months')->nullable();
            $table->string('profit_12_months')->nullable();
            $table->string('profit_months_percent')->nullable();
            $table->string('profit_3_months_percent')->nullable();
            $table->string('profit_12_months_percent')->nullable();
            $table->string('customer_months')->nullable();
            $table->string('customer_3_months')->nullable();
            $table->string('customer_12_months')->nullable();
            $table->string('debts_months')->nullable();
            $table->string('debts_3_months')->nullable();
            $table->string('debts_12_months')->nullable();
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
        Schema::dropIfExists('kpi_member_groups');
    }
};
