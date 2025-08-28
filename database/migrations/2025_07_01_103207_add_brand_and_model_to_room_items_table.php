<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('room_items', function (Blueprint $table) {
            $table->string('brand')->nullable()->after('device_type');
            $table->string('model')->nullable()->after('brand');
        });
    }

    public function down(): void
    {
        Schema::table('room_items', function (Blueprint $table) {
            $table->dropColumn(['brand', 'model']);
        });
    }
};
