<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupplyIdToSupplyProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('supply_products', function (Blueprint $table) {
            $table->integer('supply_id')->after('supplier_id');

            // remove supplier_id
            $table->dropColumn('supplier_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('supply_products', function (Blueprint $table) {
            $table->integer('supplier_id')->after('supply_id');

            // remove supply_id
            $table->dropColumn('supply_id');
        });
    }
}
