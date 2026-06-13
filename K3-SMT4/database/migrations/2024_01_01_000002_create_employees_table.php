<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('name');
            $table->string('position'); // jabatan
            $table->foreignId('department_id')->constrained('departments')->onDelete('restrict');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->date('join_date');
            $table->enum('status', ['aktif', 'tidak_aktif', 'cuti', 'resign'])->default('aktif');
            $table->string('photo_url')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
