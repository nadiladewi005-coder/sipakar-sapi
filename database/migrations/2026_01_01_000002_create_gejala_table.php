<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gejala', function (Blueprint $t) {
            $t->id();
            $t->string('kode')->unique();   // sama dengan nama gejala di CSV, mis. "painless lumps"
            $t->string('nama');
            $t->string('kelompok');
            $t->string('warna_a', 9)->default('#14746F');
            $t->string('warna_b', 9)->default('#3FA58C');
            $t->unsignedSmallInteger('urutan')->default(0);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gejala');
    }
};
