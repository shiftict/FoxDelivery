<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMethodShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('method_shippings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->float('price', 10, 0)->nullable();
            $table->enum('status', ['0', '1'])->comment(' 0 - dis active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('method_shippings');
    }
}
