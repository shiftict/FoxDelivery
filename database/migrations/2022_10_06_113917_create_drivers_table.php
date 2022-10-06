<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('vendor_id')->nullable();
            $table->bigInteger('vehicle_id')->nullable();
            $table->bigInteger('deliveries_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('user_drivers_id')->nullable();
            $table->integer('status')->nullable();
            $table->integer('days_number')->nullable();
            $table->timestamp('date_work')->nullable();
            $table->timestamp('end_date_work')->nullable();
            $table->time('time_first_from')->nullable();
            $table->time('start_secound_shift')->nullable();
            $table->time('time_first_to')->nullable();
            $table->time('end_secound_shift')->nullable();
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
        Schema::dropIfExists('drivers');
    }
}
