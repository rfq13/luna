<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChaneTransactionIdToTransactionCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // rename column to transaction_code
        Schema::table('repayments', function (Blueprint $table) {
            $table->renameColumn('transaction_id', 'transaction_code');
        });
        Schema::table('kredits', function (Blueprint $table) {
            $table->renameColumn('transaction_id', 'transaction_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // rename column to transaction_id
        Schema::table('repayments', function (Blueprint $table) {
            $table->renameColumn('transaction_code', 'transaction_id');
        });
        Schema::table('kredits', function (Blueprint $table) {
            $table->renameColumn('transaction_code', 'transaction_id');
        });
    }
}
