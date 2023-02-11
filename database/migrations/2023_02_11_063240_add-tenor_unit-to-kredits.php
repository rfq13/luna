<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTenorUnitToKredits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kredits', function (Blueprint $table) {
            $table->string("tenor_unit")->nullable()->default(NULL)->after("tenor");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kredits', function (Blueprint $table) {
            $table->dropColumn("tenor_unit");
        });
    }
}
