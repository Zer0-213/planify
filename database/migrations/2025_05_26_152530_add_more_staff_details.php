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
        Schema::table('users', static function (Blueprint $table) {
            $table->string('phone_number')->nullable()->after('email_verified_at');
        });
        Schema::table('company_users', static function (Blueprint $table) {
            $table->integer('wage')->default(0)->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', static function (Blueprint $table) {
            $table->dropColumn(['phone_number']);
        });
        Schema::table('company_users', static function (Blueprint $table) {
            $table->dropColumn(['wage']);
        });
    }
};
