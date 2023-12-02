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
        Schema::create('pelaporan_ke_dinas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('judul_pelaporan');
            $table->text('isi_laporan');
            $table->enum('visibility', ['anonim', 'public'])->default('public');
            $table->string('file_path')->nullable();
            $table->string('gambar')->nullable();
            $table->timestamp('tanggal_kejadian');
            $table->string('lokasi_kejadian');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelaporan_ke_dinas');
    }
};
