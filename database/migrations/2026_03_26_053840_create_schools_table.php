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
        Schema::create('schools', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_sekolah');
            $table->string('npsn_sekolah')->nullable();
            $table->string('alamat_sekolah')->nullable();
            $table->string('pemerintah_provinsi')->nullable();
            $table->string('instansi_pemerintah')->nullable();
            $table->string('email_sekolah')->nullable();
            $table->string('website_sekolah')->nullable();
            $table->string('nama_kepsek')->nullable();
            $table->string('nip_kepsek')->nullable();
            $table->string('logo_provinsi')->nullable();
            $table->string('logo_sekolah')->nullable();
            $table->string('ttd_kepsek')->nullable();
            $table->string('cap_sekolah')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
