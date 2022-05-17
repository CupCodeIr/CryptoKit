<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCryptoATMSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crypto_a_t_m_s', function (Blueprint $table) {
            $table->id();
            $table->unsignedMediumInteger('source_id')->unique();
            $table->string('name',100)->nullable();
            $table->decimal('lat', 10, 7);
            $table->decimal('long', 10, 7);
            $table->string('place_number',30)->nullable();
            $table->string('email',30)->nullable();
            $table->string('city',30)->nullable();
            $table->string('phone',20)->nullable();
            $table->string('postcode',10)->nullable();
            $table->string('fax',20)->nullable();
            $table->string('category',20)->nullable();
            $table->string('state',30)->nullable();
            $table->string('opening_hours')->nullable();
            $table->string('description')->nullable();
            $table->string('website')->nullable();
            $table->string('street')->nullable();
            $table->string('facebook',100)->nullable();
            $table->string('twitter',100)->nullable();
            $table->foreignId('country_id')->nullable()->constrained();
            $table->timestamp('create_on_date')->nullable();
            $table->timestamp('update_on_date')->nullable();
            $table->enum('status',['active','deactive'])->default('active');
            $table->boolean('visibility')->default(true);
            $table->timestamps();
            $table->index(['lat','long']);
            $table->index('visibility');
            $table->index('status');
            $table->index('country_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crypto_a_t_m_s');
    }
}
