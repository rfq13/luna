<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsImportTuSupplies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // add is_import to supplies
        Schema::table('supplies', function (Blueprint $table) {
            $table->boolean('is_import')->default(false)->after('total_harga');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // remove is_import from supplies
        Schema::table('supplies', function (Blueprint $table) {
            $table->dropColumn('is_import');
        });
    }
}
