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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->string('expense_name')->nullable();
            $table->decimal('amount',50,2)->nullable();
            $table->integer('payment_method')->nullable();
            $table->string('expense_date')->nullable();
            $table->string('expense_type')->nullable();
            $table->string('recurring_frequency')->nullable();
            $table->string('expense_image')->nullable();
            $table->text('notes')->nullable();
            $table->string('added_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
