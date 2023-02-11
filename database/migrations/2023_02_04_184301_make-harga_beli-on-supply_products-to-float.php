<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeHargaBeliOnSupplyProductsToFloat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // change harga_beli to float
        Schema::table('supply_products', function (Blueprint $table) {
            $table->float('harga_beli')->change();
        });
        Schema::table('supply_histories', function (Blueprint $table) {
            $table->float('harga_beli')->change();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // change harga_beli to integer
        Schema::table('supply_products', function (Blueprint $table) {
            $table->integer('harga_beli')->change();
        });
        Schema::table('supply_histories', function (Blueprint $table) {
            $table->integer('harga_beli')->change();
        });
    }
}
