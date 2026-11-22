<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Gejala extends Model
{
    protected $table = 'gejala';
    protected $guarded = [];

    /** Foto gejala: letakkan di public/images/gejala/{slug-kode}.jpg, contoh painless-lumps.jpg */
    public function getGambarUrlAttribute(): ?string
    {
        $file = 'images/gejala/' . Str::slug($this->kode) . '.jpg';

        return file_exists(public_path($file)) ? asset($file) : null;
    }
}
