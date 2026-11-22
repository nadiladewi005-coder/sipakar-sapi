<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diagnosa', function (Blueprint $t) {
            $t->id();
            $t->foreignId('penyakit_id')->nullable()->constrained('penyakit')->nullOnDelete();
            $t->decimal('persen', 5, 1)->default(0);
            $t->json('masukan');                           // {gejala_id: cf_pengguna}
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diagnosa');
    }
};
