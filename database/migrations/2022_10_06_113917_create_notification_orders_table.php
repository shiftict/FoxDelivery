<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->enum('read', ['0', '1'])->default('0')->comment('only 0 unread, 1 read');
            $table->softDeletes();
            $table->timestamps();
            $table->unsignedBigInteger('vendor_id')->nullable()->index('notification_orders_vendor_id_foreign');
            $table->unsignedBigInteger('user_id')->nullable()->index('notification_orders_user_id_foreign');
            $table->unsignedBigInteger('order_id')->nullable()->index('notification_orders_order_id_foreign');
            $table->unsignedBigInteger('admin_id')->nullable()->index('notification_orders_admin_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification_orders');
    }
}
