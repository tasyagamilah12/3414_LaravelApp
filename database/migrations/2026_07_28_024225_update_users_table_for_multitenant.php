<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Role: admin (superadmin), organizer (HIMA/Kepanitiaan), buyer (pembeli)
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'organizer', 'buyer'])->default('buyer')->after('email');
            }
            if (!Schema::hasColumn('users', 'organization_name')) {
                $table->string('organization_name')->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('organization_name');
            }
            if (!Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable()->after('avatar'); // Persiapan SSO Google (STEP 32)
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'organization_name', 'avatar', 'google_id']);
        });
    }
};