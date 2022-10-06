<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachment_stores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('ext');
            $table->string('path');
            $table->unsignedBigInteger('vendor_id')->index('attachment_stores_vendor_id_foreign');
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
        Schema::dropIfExists('attachment_stores');
    }
}
