<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aturan', function (Blueprint $t) {
            $t->id();
            $t->foreignId('penyakit_id')->constrained('penyakit')->cascadeOnDelete();
            $t->foreignId('gejala_id')->constrained('gejala')->cascadeOnDelete();
            $t->decimal('cf_pakar', 3, 2);                 // bobot keyakinan pakar (0-1)
            $t->decimal('frekuensi', 5, 4)->nullable();    // porsi kasus dataset yang memuat gejala ini
            $t->timestamps();
            $t->unique(['penyakit_id', 'gejala_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aturan');
    }
};
