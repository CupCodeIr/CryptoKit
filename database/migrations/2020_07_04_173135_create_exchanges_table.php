<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchanges', function (Blueprint $table) {
            $table->id();
            $table->string('source_id');
            $table->string('name');
            $table->string('name_persian')->nullable();
            $table->date('year_established');
            $table->string('url');
            $table->string('description')->nullable();
            $table->boolean('centralized');
            $table->json('extra')->nullable();
            $table->timestamps();
            $table->foreignId('country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exchanges');
    }
}
