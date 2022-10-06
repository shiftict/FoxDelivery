<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToNotificationOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notification_orders', function (Blueprint $table) {
            $table->foreign(['order_id'])->references(['id'])->on('orders');
            $table->foreign(['vendor_id'])->references(['id'])->on('vendors');
            $table->foreign(['admin_id'])->references(['id'])->on('users');
            $table->foreign(['user_id'])->references(['id'])->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notification_orders', function (Blueprint $table) {
            $table->dropForeign('notification_orders_order_id_foreign');
            $table->dropForeign('notification_orders_vendor_id_foreign');
            $table->dropForeign('notification_orders_admin_id_foreign');
            $table->dropForeign('notification_orders_user_id_foreign');
        });
    }
}
