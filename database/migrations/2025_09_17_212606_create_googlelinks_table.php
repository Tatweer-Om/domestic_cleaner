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
        Schema::create('googlelinks', function (Blueprint $table) {
            $table->id();
             $table->string('location_id')->nullable();
            $table->longText('google_map')->nullable();
            $table->longText('e_map')->nullable();
            $table->string('lat')->nullable();
            $table->string('lon')->nullable();
            $table->longText('address')->nullable();
            $table->string('guest_token', 64)->nullable()->index();
            $table->string('session_id', 128)->nullable()->index();
            $table->string('user_id', 255)->nullable();
            $table->string('added_by')->nullable();
         
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('googlelinks');
    }
};
