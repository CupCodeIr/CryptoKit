<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMiningPoolCoinTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_mining_pool', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coin_id')->constrained();
            $table->foreignId('mining_pool_id')->constrained('mining_pools');
            $table->unique(['coin_id','mining_pool_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coin_mining_pool');
    }
}
