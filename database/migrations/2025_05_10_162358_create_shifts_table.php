<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('company_user_id')->constrained()->onDelete('cascade');
            $table->date('shift_date');
            $table->datetime('starts_at')->nullable();
            $table->datetime('ends_at')->nullable();
            $table->integer('break_duration')->nullable(); // Duration in minutes
            $table->text('notes')->nullable();
            $table->foreignId('role_id')->nullable()->constrained()->onDelete('set null');
            $table->string('location')->nullable();
            $table->enum('status', ['scheduled', 'confirmed', 'in_progress', 'completed', 'cancelled'])->default('scheduled');


            $table->index(['starts_at', 'company_user_id']);
            $table->index(['ends_at', 'company_user_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
