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
        Schema::create('kpi_debts', function (Blueprint $table) {
            $table->id();
            $table->string('min_days')->nullable();
            $table->string('max_days')->nullable();
            $table->string('percentage')->nullable();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('group_id')->nullable();
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
        Schema::dropIfExists('kpi_debts');
    }
};
