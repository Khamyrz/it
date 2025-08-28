<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('room_items', function (Blueprint $table) {
        $table->boolean('is_full_item')->default(false)->after('status');
        $table->string('full_set_id')->nullable()->after('is_full_item');
    });
}

public function down(): void
{
    Schema::table('room_items', function (Blueprint $table) {
        $table->dropColumn('is_full_item');
        $table->dropColumn('full_set_id');
    });
}

};
