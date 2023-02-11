<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeKreditXchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // add is credit column to transactions table
        Schema::table('transactions', function (Blueprint $table) {
            $table->boolean('is_kredit')->default(false);
        });

        // create kredit table
        Schema::create('kredits', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('transaction_id')->default(0);
            $table->string('description');
            $table->integer('amount');
            $table->timestamps();
        });

        // create customer table
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('nik');
            $table->string('nama');
            $table->string('npwp');
            $table->string('alamat');
            $table->string('nohp');
            $table->integer('plafon')->default(0);
            $table->timestamps();
        });

        // delete customer field in transactions table
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('nama_customer');
            $table->dropColumn('alamat_customer');
            $table->dropColumn('npwp_customer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // delete is credit column from transactions table
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('is_kredit');
        });

        // delete kredit table
        Schema::dropIfExists('kredit');

        // delete customer table
        Schema::dropIfExists('customers');

        // add customer field in transactions table
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('nama_customer');
            $table->string('alamat_customer');
            $table->string('npwp_customer');
        });

    }
}
