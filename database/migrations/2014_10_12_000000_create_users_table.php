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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->enum('akses', ['masyarakat', 'admin', 'superadmin', 'seksi', 'pengacara', 'polisi'])->default('masyarakat');
            $table->string('full_name');
            $table->string('username')->unique();
            $table->string('foto_profil')->nullable();
            $table->string('nohp')->unique();
            $table->timestamp('nohp_verified_at')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
