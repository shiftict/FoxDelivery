<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('mobile')->nullable();
            $table->bigInteger('vendor_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->longText('lat')->nullable();
            $table->longText('long')->nullable();
            $table->longText('address')->nullable();
            $table->longText('tokenfcm')->nullable();
            $table->longText('device_key')->nullable();
            $table->longText('accessToken')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->enum('is_online', ['0', '1'])->nullable();
            $table->string('code')->nullable();
            $table->string('last_lat_driver')->nullable();
            $table->string('last_long_driver')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
