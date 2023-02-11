<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDueDateToKredits extends Migration
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
            $table->string('due_date')->default(null)->after('dp');

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
            $table->dropColumn('due_date');
        });
    }
}
