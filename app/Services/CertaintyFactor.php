<?php

namespace App\Services;

use App\Models\Penyakit;
use Illuminate\Support\Collection;

/**
 * Mesin inferensi metode Certainty Factor.
 *
 *  CF gejala   = CF pakar x CF pengguna
 *  CF gabungan = CFlama + CFbaru x (1 - CFlama)
 */
class CertaintyFactor
{
    public const PILIHAN = [
        '0' => 'Tidak',
        '0.4' => 'Ragu',
        '0.6' => 'Cukup',
        '0.8' => 'Yakin',
        '1' => 'Sangat yakin',
    ];

    /** @param array<int,float|string> $masukan  [gejala_id => CF pengguna] */
    public function hitung(array $masukan): Collection
    {
        $masukan = array_filter(array_map('floatval', $masukan), fn ($v) => $v > 0);

        return Penyakit::with('aturan.gejala')->get()->map(function (Penyakit $p) use ($masukan) {
            $cf = 0.0;
            $langkah = [];

            foreach ($p->aturan as $aturan) {
                $pengguna = $masukan[$aturan->gejala_id] ?? null;
                if (! $pengguna) {
                    continue;
                }

                $cfGejala = (float) $aturan->cf_pakar * $pengguna;
                $lama = $cf;
                $cf = $lama + $cfGejala * (1 - $lama);

                $langkah[] = [
                    'gejala' => $aturan->gejala->nama,
                    'cf_pakar' => (float) $aturan->cf_pakar,
                    'cf_pengguna' => $pengguna,
                    'cf_gejala' => $cfGejala,
                    'cf_lama' => $lama,
                    'cf_gabungan' => $cf,
                ];
            }

            return [
                'penyakit' => $p,
                'cf' => $cf,
                'persen' => round($cf * 100, 1),
                'langkah' => $langkah,
                'tingkat' => self::tingkat($cf * 100),
            ];
        })->sortByDesc('cf')->values();
    }

    public static function tingkat(float $persen): string
    {
        return match (true) {
            $persen >= 80 => 'Sangat mungkin',
            $persen >= 60 => 'Mungkin',
            $persen >= 40 => 'Perlu dicurigai',
            default => 'Kecil kemungkinan',
        };
    }
}
