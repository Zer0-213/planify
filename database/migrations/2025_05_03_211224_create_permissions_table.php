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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name'); // e.g., edit_schedule, view_schedule, etc.
            $table->string('label'); // Human-readable name (e.g., "Edit Schedule")
            $table->string('description')->nullable(); // Description of the permission

            $table->unique('name'); // Ensure unique permission names
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
