<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMiningEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mining_equipment', function (Blueprint $table) {
            $table->id();
            $table->string('source_id');
            $table->string('name');
            $table->string('name_persian')->nullable();
            $table->string('description')->nullable();
            $table->string('algorithm')->nullable();
            $table->unsignedDecimal('cost',7,2)->nullable();
            $table->unsignedBigInteger('hashes_per_second')->nullable();
            $table->string('buy_url',300)->nullable();
            $table->json('rating')->nullable();
            $table->unsignedSmallInteger('rank')->nullable();
            $table->foreignId('company_id')->nullable()->constrained('mining_companies');
            $table->enum('status',['active','deactive'])->default('active');
            $table->boolean('visibility')->default(true);
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
        Schema::dropIfExists('mining_equipment');
    }
}
