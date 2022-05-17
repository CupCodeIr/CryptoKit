<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMiningCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mining_companies', function (Blueprint $table) {
            $table->id();
            $table->string('source_id');
            $table->string('name');
            $table->string('name_persian')->nullable();
            $table->string('home_url')->nullable();
            $table->string('rank')->nullable();
            $table->string('description')->nullable();
            $table->boolean('visibility')->default(true);
            $table->enum('status',['active','deactive'])->default('active');
            $table->json('rating')->nullable();
            $table->foreignId('country_id')->nullable()->constrained();
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
        Schema::dropIfExists('mining_companies');
    }
}
