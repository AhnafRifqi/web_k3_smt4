<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('k3_documents', function (Blueprint $table) {
            $table->enum('visibility', ['public', 'restricted'])->default('public')->after('status');
            $table->json('allowed_departments')->nullable()->after('visibility');
        });
    }

    public function down(): void
    {
        Schema::table('k3_documents', function (Blueprint $table) {
            $table->dropColumn(['visibility', 'allowed_departments']);
        });
    }
};
