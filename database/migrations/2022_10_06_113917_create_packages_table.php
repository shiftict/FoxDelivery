<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('number_of_orders')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->enum('type', ['0', '1'])->default('0')->comment('only (0- peer orders) . (1- peer hourse)');
            $table->softDeletes();
            $table->timestamps();
            $table->enum('status', ['0', '1'])->default('1')->comment('1- active, 0- dis_active');
            $table->float('discount_price', 10, 0)->nullable();
            $table->integer('number_of_driver')->nullable();
            $table->integer('number_of_month')->nullable();
            $table->float('price', 10, 0)->nullable();
            $table->timestamp('start_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
    }
}
