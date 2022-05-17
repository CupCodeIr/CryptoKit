<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('symbol');
            $table->string('slug');
            $table->string('icon_path')->nullable();
            $table->unsignedDecimal('price',8,6);
            $table->unsignedBigInteger('market_cap');
            $table->string('name_persian')->nullable();
            $table->unsignedBigInteger('vol_24');
            $table->unsignedDecimal('high_24',10,6);
            $table->unsignedDecimal('low_24',10,6);
            $table->decimal('price_change_24');
            $table->unsignedDecimal('market_cap_change_percentage_24h',10,6);
            $table->unsignedBigInteger('total_supply');
            $table->unsignedBigInteger('circulating');
            $table->unsignedDecimal('ath',10,6);
            $table->dateTime('ath_date');
            $table->unsignedDecimal('atl',10,6);
            $table->dateTime('atl_date');
            $table->string('description')->nullable();
            $table->json('7d_sparkline');
            $table->string('algorithm');
            $table->string('proof_type')->nullable();
            $table->unsignedInteger('block_time');
            $table->unsignedInteger('block_reward');
            $table->unsignedBigInteger('hash_per_second')->nullable();
            $table->string('source');
            $table->json('extra')->nullable();
            $table->enum('status', ['active', 'deactive'])->default('active');
            $table->timestamps();
            $table->unique(['name', 'symbol', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coins');
    }
}
