<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('name')->nullable();
            $table->string('lat');
            $table->string('long');
            $table->string('formatted_address')->nullable();
            $table->float('price', 10, 0)->nullable();
            $table->enum('status', ['0', '1']);
            $table->unsignedBigInteger('area_id')->index('cities_area_id_foreign');
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
        Schema::dropIfExists('cities');
    }
}
