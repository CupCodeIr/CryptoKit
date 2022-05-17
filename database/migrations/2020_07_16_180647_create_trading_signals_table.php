<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradingSignalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trading_signals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coin_id')->constrained();
            $table->timestamp('time');
            $table->json("inOutVar")->nullable();
            $table->json("largetxsVar")->nullable();
            $table->json("addressesNetGrowth")->nullable();
            $table->json("concentrationVar")->nullable();
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
        Schema::dropIfExists('trading_signals');
    }
}
