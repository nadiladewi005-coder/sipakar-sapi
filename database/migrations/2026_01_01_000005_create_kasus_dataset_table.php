<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kasus_dataset', function (Blueprint $t) {
            $t->id();
            $t->foreignId('penyakit_id')->constrained('penyakit')->cascadeOnDelete();
            $t->unsignedTinyInteger('umur')->nullable();
            $t->decimal('suhu_f', 4, 1)->nullable();       // suhu tubuh dalam Fahrenheit (data asli CSV)
            $t->string('gejala_1');
            $t->string('gejala_2');
            $t->string('gejala_3');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kasus_dataset');
    }
};
