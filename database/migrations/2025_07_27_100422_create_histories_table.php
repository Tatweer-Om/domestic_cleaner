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
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->string('table_name');
            $table->integer('record_id');
            $table->string('function');
            $table->string('function_status')->comment('1 for update, 2 for delete');
            $table->integer('branch_id');
            $table->json('previous_data')->nullable();
            $table->json('updated_data')->nullable();
            $table->json('added_data')->nullable();

            $table->string('added_by');
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
