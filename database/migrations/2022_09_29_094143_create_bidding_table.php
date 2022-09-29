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
        Schema::create('bidding', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('project_name')->nullable();
            $table->string('code')->nullable();
            $table->unsignedBigInteger('fp_id')->nullable();
           
            $table->string('price')->nullable();
            $table->string('bid_guarantee')->nullable();
            $table->string('bid_day')->nullable();
            $table->date('start_day')->nullable();
            $table->date('end_day')->nullable();
            $table->text('file_request')->nullable();
            $table->text('file_bid')->nullable();
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
        Schema::dropIfExists('bidding');
    }
};
