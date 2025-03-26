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
        Schema::create('kpi_setting_technical', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('year')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->json('certificate_conditions')->nullable();
            $table->json('project_conditions')->nullable();
            $table->json('review_conditions')->nullable();
            $table->string('review_percent')->nullable();
            $table->string('project_percent')->nullable();
            $table->string('certificate_percent')->nullable();

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
        Schema::dropIfExists('kpi_setting_technical');
    }
};
