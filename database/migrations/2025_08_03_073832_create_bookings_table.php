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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('worker_id')->nullable();
            $table->integer('package_id')->nullable();
            $table->string('booking_no')->nullable();
            $table->string('location_id')->nullable();
            $table->date('start_date')->nullable();
            $table->integer('duration')->nullable(); // Changed from unsignedTinyInteger
            $table->integer('visits_count')->nullable(); // Changed from unsignedInteger
            $table->json('visits')->nullable();
            $table->string('status', 32)->nullable()->default('pending');
            $table->timestamps();
            $table->index(['user_id', 'worker_id', 'location_id', 'package_id']);
            $table->index(['status']);
            $table->string('customer_id')->nullable();
            $table->string('added_by')->nullable();
            $table->string('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
