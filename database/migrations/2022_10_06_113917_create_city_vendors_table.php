<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCityVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('city_vendors', function (Blueprint $table) {
            $table->integer('id', true);
            $table->bigInteger('user_id');
            $table->bigInteger('vendor_id');
            $table->bigInteger('city_id');
            $table->enum('status', ['0', '1'])->default('1');
            $table->softDeletes();
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
        Schema::dropIfExists('city_vendors');
    }
}
