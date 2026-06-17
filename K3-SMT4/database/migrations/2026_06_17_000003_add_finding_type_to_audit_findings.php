<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audit_findings', function (Blueprint $table) {
            // finding_type: non_conformance (NCR), conformance (OFI), observation
            $table->string('finding_type')->default('non_conformance')->after('severity');
        });
    }

    public function down(): void
    {
        Schema::table('audit_findings', function (Blueprint $table) {
            $table->dropColumn('finding_type');
        });
    }
};
