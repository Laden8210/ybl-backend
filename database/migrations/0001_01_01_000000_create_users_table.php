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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'supervisor', 'driver', 'conductor', 'passenger'])->default('passenger');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('license_number')->nullable(); // For drivers
            $table->string('employee_id')->nullable(); // For staff (supervisor, driver, conductor)
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('buses', function (Blueprint $table) {
            $table->id();
            $table->string('bus_number')->unique();
            $table->string('license_plate')->unique();
            $table->integer('capacity');
            $table->string('model')->nullable();
            $table->string('color')->nullable();
            $table->enum('status', ['active', 'maintenance', 'inactive'])->default('active');
            $table->text('features')->nullable(); // AC, WiFi, etc.
            $table->timestamps();
        });

        Schema::create('bus_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('conductor_id')->constrained('users')->onDelete('cascade');
            $table->date('assignment_date');
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['bus_id', 'assignment_date'], 'bus_date_unique');
        });

        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('route_name');
            $table->text('description')->nullable();
            $table->string('start_point');
            $table->string('end_point');
            $table->decimal('distance', 8, 2)->nullable(); // in kilometers
            $table->integer('estimated_duration')->nullable(); // in minutes
            $table->json('waypoints')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained()->onDelete('cascade');
            $table->foreignId('route_id')->constrained()->onDelete('cascade');
            $table->time('departure_time');
            $table->time('arrival_time');
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->boolean('is_recurring')->default(true);
            $table->date('effective_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['scheduled', 'departed', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();
        });

        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained()->onDelete('cascade');
            $table->foreignId('bus_assignment_id')->constrained()->onDelete('cascade');
            $table->date('trip_date');
            $table->time('actual_departure_time')->nullable();
            $table->time('actual_arrival_time')->nullable();
            $table->integer('passenger_count')->default(0);
            $table->decimal('start_latitude', 10, 8)->nullable();
            $table->decimal('start_longitude', 10, 8)->nullable();
            $table->decimal('end_latitude', 10, 8)->nullable();
            $table->decimal('end_longitude', 10, 8)->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('drop_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->onDelete('cascade');
            $table->foreignId('passenger_id')->constrained('users')->onDelete('cascade');
            $table->string('address');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 10, 8);
            $table->integer('sequence_order');
            $table->enum('status', ['requested', 'forwarded', 'confirmed', 'completed', 'cancelled'])->default('requested');
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('forwarded_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('bus_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->onDelete('cascade');
            $table->foreignId('bus_id')->constrained()->onDelete('cascade');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 10, 8);
            $table->decimal('speed', 6, 2)->nullable();
            $table->decimal('heading', 5, 2)->nullable();
            $table->timestamp('recorded_at');
            $table->timestamps();
        });

        Schema::create('bus_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained()->onDelete('cascade');
            $table->foreignId('trip_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('action'); // departure, arrival, maintenance, etc.
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // Additional data in JSON format
            $table->timestamp('log_time');
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type');
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
