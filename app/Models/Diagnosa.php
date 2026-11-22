<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Diagnosa extends Model
{
    protected $table = 'diagnosa';
    protected $guarded = [];
    protected $casts = ['masukan' => 'array', 'persen' => 'float'];

    public function penyakit(): BelongsTo
    {
        return $this->belongsTo(Penyakit::class);
    }
}
