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
        Schema::create('project', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('customer_contract')->nullable();
            $table->text('supplier_contract')->nullable();
            $table->text('supplier_receipt')->nullable();
            $table->text('company_receipt')->nullable();
            $table->text('BBNT')->nullable();
            $table->text('BBBG')->nullable();
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
        Schema::dropIfExists('project');
    }
};
