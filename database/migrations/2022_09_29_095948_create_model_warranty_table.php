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
        Schema::create('model_warranty', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('time_start')->nullable();
            $table->integer('type_warranty')->default(1)->nullable();
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
        Schema::dropIfExists('model_warranty');
    }
};
