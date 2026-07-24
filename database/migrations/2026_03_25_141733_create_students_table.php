<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nipd')->nullable();
            $table->string('nisn')->unique();
            $table->string('qrcode')->nullable();
            $table->string('name');
            $table->foreignUuid('classrooms_id')->constrained('classrooms')->onDelete('cascade');
            $table->enum('jenis_kelamin', ['Laki - Laki', 'Perempuan']);
            $table->enum('agama', ['Islam', 'Kristen', 'Katholik', 'Hindu', 'Budha', 'Khonghucu', 'Lainnya']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('status', ['active', 'non-active', 'lulus'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
