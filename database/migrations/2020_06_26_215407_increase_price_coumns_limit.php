<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IncreasePriceCoumnsLimit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coins', function (Blueprint $table) {
            //
            $table->unsignedDecimal('price',12,6)->change();
            $table->unsignedDecimal('high_24',12,6)->change();
            $table->unsignedDecimal('low_24',12,6)->change();
            $table->decimal('price_change_24',6,3)->change();
            $table->decimal('market_cap_change_percentage_24h',6,3)->change();
            $table->unsignedDecimal('ath',12,6)->nullable()->change();
            $table->unsignedDecimal('atl',12,6)->nullable()->change();
            $table->unsignedFloat('block_time')->change();
            $table->unsignedFloat('block_reward')->nullable()->change();




        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coins', function (Blueprint $table) {
            //
        });
    }
}
