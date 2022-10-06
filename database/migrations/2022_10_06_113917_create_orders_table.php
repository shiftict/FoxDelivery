<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->longText('description')->nullable();
            $table->string('phone');
            $table->enum('type_order', ['0', '1'])->comment('0 only one way, 1 only tow way');
            $table->string('from_address')->nullable();
            $table->string('to_address')->nullable();
            $table->string('lat_from')->nullable();
            $table->string('long_from')->nullable();
            $table->string('lat_to')->nullable();
            $table->string('long_to')->nullable();
            $table->timestamp('date')->nullable()->comment('Estimated time of arrival');
            $table->enum('status', ['0', '1', '2'])->default('0')->comment('0 only pending, 1 only accept, 2 only reject');
            $table->string('order_status')->nullable()->comment('0 only pending, 1 start trip, 2 received driver, 3 received customer, 4 reject');
            $table->unsignedBigInteger('vendor_id')->nullable()->index('orders_vendor_id_foreign');
            $table->unsignedBigInteger('action_by')->nullable()->index('orders_action_by_foreign');
            $table->unsignedBigInteger('delivery_id')->nullable()->index('orders_delivery_id_foreign');
            $table->unsignedBigInteger('vendor_list_package_id')->nullable()->index('orders_vendor_list_package_id_foreign');
            $table->unsignedBigInteger('created_by')->index('orders_created_by_foreign');
            $table->softDeletes();
            $table->timestamps();
            $table->enum('cancel', ['0', '1'])->nullable()->default('0');
            $table->string('color')->nullable();
            $table->string('block')->nullable();
            $table->string('home')->nullable();
            $table->string('sabil')->nullable();
            $table->string('street')->nullable();
            $table->enum('create_by_admin', ['0', '1'])->nullable()->default('0')->comment('only : (0 - by vendor) (1 - by admin)');
            $table->enum('package_type', ['0', '1'])->nullable()->comment('0 peer order | 1 peer hours');
            $table->timestamp('date_from')->nullable();
            $table->timestamp('date_to')->nullable();
            $table->time('time_from')->nullable();
            $table->time('time_to')->nullable();
            $table->timestamp('date_from_driver')->nullable();
            $table->timestamp('date_to_driver')->nullable();
            $table->time('time_from_driver')->nullable();
            $table->time('time_to_driver')->nullable();
            $table->enum('delay_pic_up', ['0', '1'])->nullable();
            $table->enum('delay_delivery', ['0', '1'])->nullable();
            $table->integer('items')->nullable();
            $table->integer('payment_method')->nullable()->comment('0 - online | 1 - cash');
            $table->float('totale_amount', 10, 0)->nullable();
            $table->integer('city_id')->nullable();
            $table->string('order_reference')->nullable();
            $table->timestamp('pick_up')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
