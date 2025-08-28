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
            $table->boolean('is_full_set_item')->default(false)->after('status');
            $table->string('full_set_id')->nullable()->after('is_full_set_item');
            
            // Add index for better performance
            $table->index('full_set_id');
            $table->index(['is_full_set_item', 'full_set_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_items', function (Blueprint $table) {
            $table->dropIndex(['room_items_full_set_id_index']);
            $table->dropIndex(['room_items_is_full_set_item_full_set_id_index']);
            $table->dropColumn(['is_full_set_item', 'full_set_id']);
        });
    }
};