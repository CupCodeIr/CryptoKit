<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMiningPoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mining_pools', function (Blueprint $table) {
            $table->id();
            $table->unsignedMediumInteger("source_id");
            $table->string("name");
            $table->string("name_persian")->nullable();
            $table->string("description")->nullable();
            $table->boolean("can_merge_mining")->nullable();
            $table->boolean("tx_fee_shared_with_miner")->nullable();
            $table->string("homepage_url")->nullable();
            $table->string("twitter",30)->nullable();
            $table->string("average_fee",10)->nullable();
            $table->json("pool_features")->nullable();
            $table->json("fee_expanded")->nullable();
            $table->json("minimum_payout")->nullable();
            $table->json("server_locations")->nullable();
            $table->json("payment_type")->nullable();
            $table->json("merged_mining_coins")->nullable();
            $table->json("rating")->nullable();
            $table->boolean("visibility")->default(true);
            $table->enum("status",['active','deactive'])->default('active');
            $table->unsignedSmallInteger("rank")->nullable();
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
        Schema::dropIfExists('mining_pools');
    }
}
