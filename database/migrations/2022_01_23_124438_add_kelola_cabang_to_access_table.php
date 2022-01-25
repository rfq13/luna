<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKelolaCabangToAccessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('access', function (Blueprint $table) {
            $table->integer("kelola_cabang")->nullable()->default(0)->after("kelola_laporan");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('access', function (Blueprint $table) {
            $table->dropColumn("kelola_cabang");
        });
    }
}
