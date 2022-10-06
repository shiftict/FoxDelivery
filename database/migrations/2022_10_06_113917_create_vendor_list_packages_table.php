<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorListPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_list_packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('number_of_order')->nullable()->comment('number of order only nullable');
            $table->string('pricing')->comment('price of package');
            $table->timestamp('date_from')->nullable()->comment('date start package');
            $table->timestamp('date_to')->nullable()->comment('date expired package');
            $table->enum('status', ['0', '1'])->default('1');
            $table->unsignedBigInteger('vendor_id')->index('vendor_list_packages_vendor_id_foreign');
            $table->bigInteger('user_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->enum('payment', ['0', '1'])->nullable()->default('0');
            $table->bigInteger('package_id')->nullable();
            $table->longText('driver_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_list_packages');
    }
}
