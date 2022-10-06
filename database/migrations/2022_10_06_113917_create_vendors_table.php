<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->string('lat');
            $table->string('long');
            $table->longText('city')->nullable();
            $table->enum('status', ['0', '1'])->comment('only 0 in-active, 1 active');
            $table->string('code')->nullable();
            $table->string('home')->nullable();
            $table->string('block')->nullable();
            $table->string('sabil')->nullable();
            $table->bigInteger('citys')->nullable();
            $table->string('street')->nullable();
            $table->unsignedBigInteger('created_by')->index('vendors_created_by_foreign');
            $table->bigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('user_id')->index('vendors_user_id_foreign');
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
        Schema::dropIfExists('vendors');
    }
}
