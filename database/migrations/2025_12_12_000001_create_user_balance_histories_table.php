<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_balance_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('balance', 20, 8);
            $table->string('action');
            $table->decimal('credit', 20, 8)->default(0);
            $table->decimal('debit', 20, 8)->default(0);
            $table->text('description')->nullable();
            $table->string('reference_type')->nullable(); // Order, Trade, etc.
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index(['user_id', 'created_at']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_balance_histories');
    }
};
