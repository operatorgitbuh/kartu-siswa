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
        Schema::create('cards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('student_id')->unique()->constrained('students')->onDelete('cascade');
            $table->foreignUuid('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignUuid('background_id')->nullable()->constrained('backgrounds')->onDelete('set null');
            $table->string('foto')->nullable();
            $table->enum('status', ['active', 'expired'])->default('active');
            $table->date('exp_date')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
