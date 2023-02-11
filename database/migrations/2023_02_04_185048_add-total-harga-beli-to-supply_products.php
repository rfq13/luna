<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalHargaBeliToSupplyProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // add total_harga_beli to supply_products with undefined length
        Schema::table('supply_products', function (Blueprint $table) {
            // long float
            $table->integer('total_harga_beli')->after('harga_beli');
        });
        Schema::table('supply_histories', function (Blueprint $table) {
            $table->integer('total_harga_beli')->after('harga_beli');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // remove total_harga_beli from supply_products
        Schema::table('supply_products', function (Blueprint $table) {
            $table->dropColumn('total_harga_beli');
        });
        Schema::table('supply_histories', function (Blueprint $table) {
            $table->dropColumn('total_harga_beli');
        });

    }
}
