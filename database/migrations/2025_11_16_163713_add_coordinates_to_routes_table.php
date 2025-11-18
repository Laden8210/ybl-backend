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
        Schema::table('routes', function (Blueprint $table) {
            $table->decimal('start_latitude', 10, 8)->nullable()->after('end_point');
            $table->decimal('start_longitude', 10, 8)->nullable()->after('start_latitude');
            $table->decimal('end_latitude', 10, 8)->nullable()->after('start_longitude');
            $table->decimal('end_longitude', 10, 8)->nullable()->after('end_latitude');

            // Update existing columns to match the new requirements
            $table->text('waypoints')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->dropColumn([
                'start_latitude',
                'start_longitude',
                'end_latitude',
                'end_longitude'
            ]);

            // Revert waypoints column if needed
            $table->json('waypoints')->nullable()->change();
        });
    }
};
