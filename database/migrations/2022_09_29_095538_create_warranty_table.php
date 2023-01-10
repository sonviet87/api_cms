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
        Schema::create('warranty', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('fp_id')->nullable();
            $table->date('start_day')->nullable();
            $table->date('end_day')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('file_warranty')->nullable();
            $table->text('details')->nullable();
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
        Schema::dropIfExists('warranty');
    }
};
