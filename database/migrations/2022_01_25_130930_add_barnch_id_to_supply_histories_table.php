<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBarnchIdToSupplyHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supply_histories', function (Blueprint $table) {
            $table->integer("branch_id")->nullable()->default(0)->after("supplier_id");
        });
        Schema::table('supplies', function (Blueprint $table) {
            $table->integer("branch_id")->nullable()->default(0)->after("supplier_id");
        });
        Schema::table('users', function (Blueprint $table) {
            $table->integer("branch_id")->nullable()->default(0)->after("remember_token");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supply_histories', function (Blueprint $table) {
            $table->dropColumn("branch_id");
        });
        Schema::table('supplies', function (Blueprint $table) {
            $table->dropColumn("branch_id");
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn("branch_id");
        });
    }
}
