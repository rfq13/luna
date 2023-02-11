<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FromSupplyIdToSupplyProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supply_products', function (Blueprint $table) {
            $table->integer('from_supply_id')->nullable()->default(0)->after('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supply_products', function (Blueprint $table) {
            $table->dropColumn('from_supply_id');
        });
    }
}
