<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penyakit extends Model
{
    protected $table = 'penyakit';
    protected $guarded = [];
    protected $casts = [
        'wajib_lapor' => 'boolean',
        'saran_sekarang' => 'array',
        'saran_perawatan' => 'array',
        'saran_pencegahan' => 'array',
    ];

    public function aturan(): HasMany
    {
        return $this->hasMany(Aturan::class)->orderByDesc('cf_pakar');
    }

    public function kasus(): HasMany
    {
        return $this->hasMany(KasusDataset::class);
    }
}
