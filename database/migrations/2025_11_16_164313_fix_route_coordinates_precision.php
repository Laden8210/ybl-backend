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
            // First, drop the existing columns if they exist
            if (Schema::hasColumn('routes', 'start_latitude')) {
                $table->dropColumn('start_latitude');
            }
            if (Schema::hasColumn('routes', 'start_longitude')) {
                $table->dropColumn('start_longitude');
            }
            if (Schema::hasColumn('routes', 'end_latitude')) {
                $table->dropColumn('end_latitude');
            }
            if (Schema::hasColumn('routes', 'end_longitude')) {
                $table->dropColumn('end_longitude');
            }

            // Add the columns with correct precision
            $table->decimal('start_latitude', 11, 8)->nullable()->after('end_point');
            $table->decimal('start_longitude', 11, 8)->nullable()->after('start_latitude');
            $table->decimal('end_latitude', 11, 8)->nullable()->after('start_longitude');
            $table->decimal('end_longitude', 11, 8)->nullable()->after('end_latitude');
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
        });
    }
};
