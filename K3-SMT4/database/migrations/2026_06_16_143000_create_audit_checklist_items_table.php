<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained()->onDelete('cascade');
            $table->string('item_number');
            $table->text('description');
            $table->string('standard_ref')->nullable();
            $table->enum('evidence_type', ['document', 'form_submission', 'manual'])->default('manual');
            $table->unsignedBigInteger('evidence_id')->nullable();
            $table->enum('conformance_status', [
                'conforming', 'minor_nc', 'major_nc', 'observation', 'not_assessed',
            ])->default('not_assessed');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_checklist_items');
    }
};
