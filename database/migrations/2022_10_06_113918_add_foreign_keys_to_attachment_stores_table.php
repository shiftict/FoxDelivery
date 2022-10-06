<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToAttachmentStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attachment_stores', function (Blueprint $table) {
            $table->foreign(['vendor_id'])->references(['id'])->on('vendors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attachment_stores', function (Blueprint $table) {
            $table->dropForeign('attachment_stores_vendor_id_foreign');
        });
    }
}
