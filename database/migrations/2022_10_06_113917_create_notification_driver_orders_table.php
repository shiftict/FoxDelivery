<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationDriverOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_driver_orders', function (Blueprint $table) {
            $table->integer('id', true);
            $table->bigInteger('order_id');
            $table->bigInteger('user_id');
            $table->bigInteger('driver_id');
            $table->timestamp('read_at')->nullable();
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
        Schema::dropIfExists('notification_driver_orders');
    }
}
