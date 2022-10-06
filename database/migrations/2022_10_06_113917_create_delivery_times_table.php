<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_times', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->time('time_first_from')->nullable()->comment('time start delivery');
            $table->time('time_first_to')->nullable()->comment('time expired delivery');
            $table->enum('status', ['0', '1'])->default('1');
            $table->unsignedBigInteger('vendor_id')->index('delivery_times_vendor_id_foreign');
            $table->unsignedBigInteger('vendor_list_package_id')->index('delivery_times_vendor_list_package_id_foreign');
            $table->unsignedBigInteger('delivery_id')->nullable()->index('delivery_times_delivery_id_foreign');
            $table->softDeletes();
            $table->timestamps();
            $table->time('start_secound_shift')->nullable();
            $table->time('end_secound_shift')->nullable();
            $table->float('hours', 10, 0)->nullable();
            $table->bigInteger('vehicle')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_times');
    }
}
