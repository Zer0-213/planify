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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name'); // e.g., admin, manager, staff
            $table->string('label'); // Human-readable label (e.g., "Administrator")
            $table->foreignId("company_id")->constrained('companies')->onDelete('cascade'); // Foreign key to companies customTable

            $table->unique('name'); // Ensure unique role names
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
