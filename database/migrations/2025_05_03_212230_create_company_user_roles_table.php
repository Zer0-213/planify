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
        Schema::create('company_user_roles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('company_user_id')->constrained('company_users')->onDelete('cascade'); // Reference to company_user
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade'); // Reference to company_role the user within the company
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_user_roles');
    }
};
