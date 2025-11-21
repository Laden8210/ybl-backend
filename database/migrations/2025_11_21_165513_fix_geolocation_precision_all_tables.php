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
        Schema::table('trips', function (Blueprint $table) {
            $table->decimal('start_latitude', 11, 8)->nullable()->change();
            $table->decimal('start_longitude', 11, 8)->nullable()->change();
            $table->decimal('end_latitude', 11, 8)->nullable()->change();
            $table->decimal('end_longitude', 11, 8)->nullable()->change();
        });

        Schema::table('bus_locations', function (Blueprint $table) {
            $table->decimal('latitude', 11, 8)->change();
            $table->decimal('longitude', 11, 8)->change();
        });

        Schema::table('drop_points', function (Blueprint $table) {
            $table->decimal('latitude', 11, 8)->change();
            $table->decimal('longitude', 11, 8)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->decimal('start_latitude', 10, 8)->nullable()->change();
            $table->decimal('start_longitude', 10, 8)->nullable()->change();
            $table->decimal('end_latitude', 10, 8)->nullable()->change();
            $table->decimal('end_longitude', 10, 8)->nullable()->change();
        });

        Schema::table('bus_locations', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->change();
            $table->decimal('longitude', 10, 8)->change();
        });

        Schema::table('drop_points', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->change();
            $table->decimal('longitude', 10, 8)->change();
        });
    }
};
