<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_status', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->longText('name');
            $table->string('color')->nullable();
            $table->longText('icon')->nullable();
            $table->enum('status', ['0', '1'])->default('1');
            $table->timestamp('updated_at')->nullable();
            $table->softDeletes();
            $table->timestamp('created_at')->nullable();
            $table->enum('decrement', ['0', '1'])->nullable()->default('0');
            $table->enum('defulte_status', ['0', '1'])->nullable()->default('0');
            $table->string('cards_color')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_status');
    }
}
