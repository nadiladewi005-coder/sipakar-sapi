<?php

namespace App\Console\Commands;

use App\Models\Aturan;
use App\Models\Gejala;
use App\Models\Penyakit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportDataset extends Command
{
    protected $signature = 'sipakar:import-dataset
                            {file=database/data/animal_disease_dataset.csv : Lokasi file CSV}
                            {--hewan=cow : Jenis hewan yang diimpor}';

    protected $description = 'Impor dataset CSV ke tabel kasus_dataset dan hitung frekuensi gejala per penyakit';

    /** Nama penyakit di CSV => kode penyakit di database */
    private const PETA = [
        'anthrax' => 'antraks',
        'blackleg' => 'blackleg',
        'foot and mouth' => 'pmk',
        'lumpy virus' => 'lsd',
        'pneumonia' => 'pneumonia',
    ];

    public function handle(): int
    {
        $path = base_path($this->argument('file'));
        if (! is_file($path)) {
            $this->error("File tidak ditemukan: {$path}");

            return self::FAILURE;
        }

        $penyakit = Penyakit::pluck('id', 'kode');
        if ($penyakit->isEmpty()) {
            $this->error('Tabel penyakit kosong. Jalankan dulu: php artisan migrate --seed');

            return self::FAILURE;
        }

        DB::table('kasus_dataset')->truncate();

        $fh = fopen($path, 'r');
        $head = array_map('trim', fgetcsv($fh));
        $hewan = strtolower($this->option('hewan'));

        $buffer = [];
        $total = [];   // [penyakit_id => jumlah kasus]
        $muncul = [];  // [penyakit_id][kode gejala] => jumlah kasus
        $jumlah = 0;

        while (($row = fgetcsv($fh)) !== false) {
            $r = array_combine($head, $row);
            if (strtolower(trim($r['Animal'])) !== $hewan) {
                continue;
            }

            $kodePenyakit = self::PETA[strtolower(trim($r['Disease']))] ?? null;
            $pid = $kodePenyakit ? ($penyakit[$kodePenyakit] ?? null) : null;
            if (! $pid) {
                continue;
            }

            $gejala = [trim($r['Symptom 1']), trim($r['Symptom 2']), trim($r['Symptom 3'])];

            $buffer[] = [
                'penyakit_id' => $pid,
                'umur' => (int) $r['Age'],
                'suhu_f' => (float) $r['Temperature'],
                'gejala_1' => $gejala[0],
                'gejala_2' => $gejala[1],
                'gejala_3' => $gejala[2],
            ];

            $total[$pid] = ($total[$pid] ?? 0) + 1;
            foreach (array_unique($gejala) as $g) {
                $muncul[$pid][$g] = ($muncul[$pid][$g] ?? 0) + 1;
            }

            $jumlah++;
            if (count($buffer) >= 1000) {
                DB::table('kasus_dataset')->insert($buffer);
                $buffer = [];
            }
        }
        fclose($fh);

        if ($buffer) {
            DB::table('kasus_dataset')->insert($buffer);
        }

        // Simpan frekuensi gejala pada tabel aturan
        $gejalaId = Gejala::pluck('id', 'kode');
        foreach (Aturan::all() as $aturan) {
            $kode = $gejalaId->search($aturan->gejala_id);
            $n = $total[$aturan->penyakit_id] ?? 0;
            $aturan->frekuensi = $n ? round(($muncul[$aturan->penyakit_id][$kode] ?? 0) / $n, 4) : null;
            $aturan->save();
        }

        $this->info("Selesai: {$jumlah} kasus '{$hewan}' diimpor dan frekuensi gejala diperbarui.");

        return self::SUCCESS;
    }
}
