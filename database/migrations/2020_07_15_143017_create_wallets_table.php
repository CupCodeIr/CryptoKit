<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->string("source_id");
            $table->string("name");
            $table->string("name_persian")->nullable();
            $table->string("description")->nullable();
            $table->string("security")->nullable();
            $table->string("anonymity")->nullable();
            $table->string("ease_of_use")->nullable();
            $table->string("source_url")->nullable();
            $table->string("download_url")->nullable();
            $table->boolean("has_trading_facilities")->nullable();
            $table->json("wallet_features")->nullable();
            $table->json("platform")->nullable();
            $table->json("rating")->nullable();
            $table->string("rank")->nullable();
            $table->boolean("visibility")->default(true);
            $table->enum("status",['active','deactive'])->default("active");
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
        Schema::dropIfExists('wallets');
    }
}
