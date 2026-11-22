<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penyakit', function (Blueprint $t) {
            $t->id();
            $t->string('kode')->unique();
            $t->string('nama');
            $t->string('status')->nullable();
            $t->text('deskripsi')->nullable();
            $t->boolean('wajib_lapor')->default(false);
            $t->json('saran_sekarang')->nullable();
            $t->json('saran_perawatan')->nullable();
            $t->json('saran_pencegahan')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyakit');
    }
};
