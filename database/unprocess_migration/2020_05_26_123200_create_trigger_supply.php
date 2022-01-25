<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTriggerSupply extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE TRIGGER tg_pasok_barang AFTER INSERT ON supplies FOR EACH ROW
            BEGIN
                UPDATE products p SET p.stok = p.stok + NEW.jumlah WHERE p.id = NEW.product_id;
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER tg_pasok_barang');
    }
}
