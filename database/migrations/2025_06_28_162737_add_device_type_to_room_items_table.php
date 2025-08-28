<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeviceTypeToRoomItemsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('room_items', function (Blueprint $table) {
            $table->string('device_type')->nullable()->after('device_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('room_items', function (Blueprint $table) {
            $table->dropColumn('device_type');
        });
    }
}
