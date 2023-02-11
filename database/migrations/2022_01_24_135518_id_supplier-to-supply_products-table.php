<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IdSupplierToSupplyProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supply_products', function (Blueprint $table) {
            $table->renameColumn("id_pemasok", "supplier_id");
            $table->integer("ppn")->nullable()->default(0)->after("harga_beli");
            $table->dropColumn("pemasok");
            $table->integer("product_id")->after("kode_barang");
            $table->dropColumn("nama_barang");
            $table->dropColumn("kode_barang");
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
            $table->renameColumn("supplier_id", "id_pemasok");
            $table->string('pemasok')->after("id_pemasok");
            $table->dropColumn("ppn");
            $table->dropColumn("product_id");
            $table->string("kode_barang")->after("id");
            $table->string("nama_barang")->after("kode_barang");
        });
    }
}
