<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShowStatusColumnToExchanges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exchanges', function (Blueprint $table) {
            //
            $table->set('status',['active','deactive','show','hidden'])->default('deactive,hidden');
            $table->string('source_id')->unique()->change();
            $table->date('year_established')->nullable()->change();
            $table->string('url')->nullable()->change();
            $table->boolean('centralized')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exchanges', function (Blueprint $table) {
            //
        });
    }
}
