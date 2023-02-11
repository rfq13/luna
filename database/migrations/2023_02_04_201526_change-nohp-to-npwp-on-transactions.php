<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeNohpToNpwpOnTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Path: database/migrations/2023_02_04_201526_change-nohp-to-npwp-on-transactions.php
        Schema::table('transactions', function (Blueprint $table) {
            $table->renameColumn('nohp_customer', 'npwp_customer');

            // add nik column after npwp
            $table->string('nik_customer')->after('alamat_customer');
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
        Schema::table('transactions', function (Blueprint $table) {
            $table->renameColumn('npwp_customer', 'nohp_customer');

            // drop nik column
            $table->dropColumn('nik_customer');
        });
    }
}
