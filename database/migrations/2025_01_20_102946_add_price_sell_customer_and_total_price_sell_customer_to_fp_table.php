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
        Schema::table('fp_details', function (Blueprint $table) {
            $table->string('price_sell_customer')->nullable()->after('price_sell');
            $table->string('total_price_sell_customer')->nullable()->after('total_sell');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fp_details', function (Blueprint $table) {
            $table->dropColumn('price_sell_customer');
            $table->dropColumn('total_price_sell_customer');
        });
    }
};
