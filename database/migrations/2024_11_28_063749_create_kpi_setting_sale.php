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
        Schema::create('kpi_setting_sale', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('year')->nullable();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('sales_months')->nullable();
            $table->string('sales_3_months')->nullable();
            $table->string('sales_12_months')->nullable();
            $table->string('sales_months_percent')->nullable();
            $table->string('sales_3_months_percent')->nullable();
            $table->string('sales_12_months_percent')->nullable();
            $table->string('current_sale_months')->nullable();
            $table->string('current_sale_3_months')->nullable();
            $table->string('current_sale_12_months')->nullable();
            $table->string('current_sale_months_percent')->nullable();
            $table->string('current_sale_3_months_percent')->nullable();
            $table->string('current_sale_12_months_percent')->nullable();
            $table->string('debts_1_percent')->nullable();
            $table->string('debts_3_percent')->nullable();
            $table->string('debts_12_percent')->nullable();
            $table->string('debts_text')->default('Điều kiện đạt công nợ')->nullable();
            $table->string('staff_manager_text')->default('Quản lý nhân viên')->nullable();
            $table->string('sale_text')->default('Mục tiêu doanh thu')->nullable();
            $table->string('current_sale_text')->default('Mục tiêu doanh số hiện hữu')->nullable();

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
        Schema::dropIfExists('kpi_setting_sale');
    }
};
