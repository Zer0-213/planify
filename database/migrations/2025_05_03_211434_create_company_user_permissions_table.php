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
        Schema::create('company_user_permissions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('company_user_id')->constrained('company_users')->onDelete('cascade');
            $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade');

            $table->unique(['company_user_id', 'permission_id']); // Ensure no duplicate
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_user_permissions');
    }
};
