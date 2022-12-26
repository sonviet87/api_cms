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
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fp_id');
            $table->dateTime('date_over')->nullable();
            $table->string('number_date_over')->nullable();
            $table->string('name')->nullable();
            $table->string('pay_first')->nullable();
            $table->string('pay_second')->nullable();
            $table->string('deposit_percent')->nullable();
            $table->string('debt_percent')->nullable();
            $table->string('total_debt')->nullable();
            $table->string('isDone')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
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
        Schema::dropIfExists('debts');
    }
};
