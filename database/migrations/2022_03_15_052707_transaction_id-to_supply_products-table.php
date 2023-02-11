<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TransactionIdToSupplyProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supply_products', function (Blueprint $table) {
            $table->string('transaction_id')->nullable()->default(null)->after("show_stock");
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
            $table->dropColumn('transaction_id');
        });
    }
}
