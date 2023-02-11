<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtherHargaToProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // tambahkan harga_ecer, harga_grosir, harga_khusus, harga_extra di tabel product
        Schema::table('products', function (Blueprint $table) {
            $table->integer('harga_grosir')->nullable()->after('harga');
            $table->integer('harga_khusus')->nullable()->after('harga_grosir');
            $table->integer('harga_extra')->nullable()->after('harga_khusus');

            // ubah harga menjadi harga_ecer
            $table->renameColumn('harga', 'harga_ecer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // hapus harga_ecer, harga_grosir, harga_khusus, harga_extra di tabel product
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('harga_grosir');
            $table->dropColumn('harga_khusus');
            $table->dropColumn('harga_extra');

            // ubah harga_ecer menjadi harga
            $table->renameColumn('harga_ecer', 'harga');
        });
    }
}
