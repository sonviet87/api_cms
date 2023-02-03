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
            $table->string('file_customer_invoice')->nullable();
            $table->string('file_company_receipt')->nullable();
            $table->string('file_bbbg')->nullable();
            $table->string('file_customer_invoice_url')->nullable();
            $table->string('file_company_receipt_url')->nullable();
            $table->string('file_bbbg_url')->nullable();
            $table->text('file_ncc')->nullable();
            $table->string('number_invoice')->nullable();
            $table->text('notes')->nullable();
            $table->string('code_contract')->nullable();
            $table->string('isCodeContract')->default(0);
            $table->dateTime('date_invoice')->nullable();
            $table->dateTime('date_shipping')->nullable();
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
