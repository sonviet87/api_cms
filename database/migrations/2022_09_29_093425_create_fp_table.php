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
        Schema::create('fp', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('user_assign')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->string('shipping_charges')->nullable();
            $table->string('shipping_charges_percent')->nullable();
            $table->string('guest_costs')->nullable();
            $table->string('guest_costs_percent')->nullable();
            $table->string('deployment_costs')->nullable();
            $table->string('deployment_costs_percent')->nullable();
            $table->string('interest')->nullable();
            $table->string('interest_percent')->nullable();
            $table->string('commission')->nullable();
            $table->string('commission_percent')->nullable();
            $table->string('tax')->nullable();
            $table->string('bids_cost')->nullable();
            $table->string('bids_cost_percent')->nullable();
            $table->string('status')->default(0);
            $table->string('selling')->default(0);
            $table->string('margin')->default(0);
            $table->string('total_sell')->default(0);
            $table->string('net_profit')->default(0);
            $table->string('net_profit_percent')->default(0);
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
        Schema::dropIfExists('fp');
    }
};
