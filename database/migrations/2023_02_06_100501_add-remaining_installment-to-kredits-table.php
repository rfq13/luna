<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemainingInstallmentToKreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // add column to kredits table
        Schema::table('kredits', function (Blueprint $table) {
            // add dp and tenor column
            $table->integer('dp')->default(0)->after('amount');
            $table->integer('tenor')->default(0)->after('amount');
            // add remaining_installment column
            $table->integer('remaining_installment')->default(0)->after('amount');

        });

        // drop transaction_code column from repayments table, then add kredit_id column
        Schema::table('repayments', function (Blueprint $table) {
            $table->dropColumn('transaction_code');
            $table->unsignedBigInteger('kredit_id')->after('customer_id');
            $table->foreign('kredit_id')->references('id')->on('kredits')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // drop column from kredits table
        Schema::table('kredits', function (Blueprint $table) {
            $table->dropColumn('dp');
            $table->dropColumn('tenor');
            $table->dropColumn('remaining_installment');
        });

        // drop kredit_id column from repayments table, then add transaction_code column
        Schema::table('repayments', function (Blueprint $table) {
            $table->dropForeign('repayments_kredit_id_foreign');
            $table->dropColumn('kredit_id');
            $table->string('transaction_code')->default(null);
        });
    }
}
