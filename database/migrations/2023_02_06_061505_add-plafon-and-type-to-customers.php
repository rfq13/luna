<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlafonAndTypeToCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add column plafon to table customers
        Schema::table('customers', function (Blueprint $table) {
            $table->integer('plafon')->after('alamat')->nullable();
            $table->string('type')->after('plafon')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop column plafon from table customers
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('plafon');
            $table->dropColumn('type');
        });
    }
}
