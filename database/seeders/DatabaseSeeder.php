<?php

namespace Database\Seeders;

use App\Models\Aturan;
use App\Models\Gejala;
use App\Models\Penyakit;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $warna = [
            'Mulut' => ['#C2452D', '#F2B544'],
            'Kuku dan kaki' => ['#14746F', '#7BD3A9'],
            'Kulit dan otot' => ['#6B3FA0', '#E8833A'],
            'Pernapasan' => ['#1F5FA8', '#5FCFE0'],
            'Kondisi umum' => ['#0B3D2E', '#3FA58C'],
        ];

        // [kode di CSV, nama Indonesia, kelompok]
        $gejala = [
            ['blisters on mouth', 'Lepuh di mulut', 'Mulut'],
            ['blisters on tongue', 'Lepuh di lidah', 'Mulut'],
            ['blisters on gums', 'Lepuh di gusi', 'Mulut'],
            ['sores on mouth', 'Luka di mulut', 'Mulut'],
            ['sores on tongue', 'Luka di lidah', 'Mulut'],
            ['sores on gums', 'Luka di gusi', 'Mulut'],
            ['blisters on hooves', 'Lepuh di kuku', 'Kuku dan kaki'],
            ['sores on hooves', 'Luka di kuku', 'Kuku dan kaki'],
            ['lameness', 'Pincang', 'Kuku dan kaki'],
            ['difficulty walking', 'Sulit berjalan', 'Kuku dan kaki'],
            ['painless lumps', 'Benjolan tanpa nyeri', 'Kulit dan otot'],
            ['crackling sound', 'Bunyi kretek di bawah kulit', 'Kulit dan otot'],
            ['swelling in muscle', 'Bengkak di otot', 'Kulit dan otot'],
            ['swelling in limb', 'Bengkak di kaki', 'Kulit dan otot'],
            ['swelling in neck', 'Bengkak di leher', 'Kulit dan otot'],
            ['swelling in abdomen', 'Bengkak di perut', 'Kulit dan otot'],
            ['swelling in extremities', 'Bengkak di ujung tubuh', 'Kulit dan otot'],
            ['shortness of breath', 'Sesak napas', 'Pernapasan'],
            ['chest discomfort', 'Dada terasa sakit', 'Pernapasan'],
            ['loss of appetite', 'Nafsu makan hilang', 'Kondisi umum'],
            ['depression', 'Lesu dan murung', 'Kondisi umum'],
            ['fatigue', 'Mudah lelah', 'Kondisi umum'],
            ['sweats', 'Berkeringat', 'Kondisi umum'],
            ['chills', 'Menggigil', 'Kondisi umum'],
        ];

        foreach ($gejala as $i => [$kode, $nama, $kelompok]) {
            Gejala::updateOrCreate(['kode' => $kode], [
                'nama' => $nama,
                'kelompok' => $kelompok,
                'urutan' => $i,
                'warna_a' => $warna[$kelompok][0],
                'warna_b' => $warna[$kelompok][1],
            ]);
        }

        // Bobot CF pakar bersifat CONTOH AWAL dan harus divalidasi dokter hewan/pakar.
        $penyakit = [
            [
                'kode' => 'antraks', 'nama' => 'Antraks', 'status' => 'Menular ke manusia', 'wajib_lapor' => true,
                'deskripsi' => 'Infeksi bakteri berbahaya. Jangan membedah bangkai dan segera lapor ke dinas peternakan dan puskesmas.',
                'saran_sekarang' => ['Jangan membedah atau menyembelih sapi yang diduga antraks', 'Jauhkan sapi lain, orang, dan anjing dari sapi sakit atau bangkai', 'Segera hubungi dokter hewan atau dinas peternakan setempat', 'Bila terpaksa menyentuh, pakai sarung tangan dan masker, lalu cuci tangan dengan sabun'],
                'saran_perawatan' => ['Catat sapi yang sakit dan yang pernah berkontak dengannya', 'Pengobatan dan penanganan bangkai harus dipandu petugas', 'Jangan konsumsi atau jual daging dari sapi yang mati mendadak'],
                'saran_pencegahan' => ['Ikuti program vaksinasi dari dinas peternakan', 'Jauhi lahan yang pernah menjadi lokasi kasus'],
                'aturan' => ['sweats' => .6, 'chills' => .6, 'fatigue' => .6, 'shortness of breath' => .6, 'chest discomfort' => .6, 'swelling in abdomen' => .4, 'swelling in extremities' => .4, 'swelling in neck' => .4],
            ],
            [
                'kode' => 'blackleg', 'nama' => 'Blackleg (Radang Paha)', 'status' => 'Cepat mematikan', 'wajib_lapor' => false,
                'deskripsi' => 'Infeksi bakteri pada otot dengan bunyi kretek di bawah kulit. Pisahkan sapi sakit dan minta penanganan dokter hewan.',
                'saran_sekarang' => ['Pisahkan sapi sakit dari kawanan', 'Hubungi dokter hewan segera karena penyakit ini berjalan sangat cepat', 'Jangan memindahkan atau mengubur bangkai sembarangan, minta arahan petugas'],
                'saran_perawatan' => ['Sediakan tempat teduh, bersih, dengan air minum yang mudah dijangkau', 'Catat suhu tubuh dan nafsu makan', 'Antibiotik hanya atas resep dokter hewan'],
                'saran_pencegahan' => ['Vaksinasi sesuai anjuran dokter hewan', 'Hindari luka pada kulit dan jaga kandang tetap bersih'],
                'aturan' => ['crackling sound' => .9, 'swelling in muscle' => .7, 'swelling in limb' => .7, 'lameness' => .6, 'difficulty walking' => .5, 'depression' => .4, 'loss of appetite' => .3],
            ],
            [
                'kode' => 'pmk', 'nama' => 'Penyakit Mulut dan Kuku (PMK)', 'status' => 'Menular sangat cepat', 'wajib_lapor' => true,
                'deskripsi' => 'Infeksi virus dengan lepuh di mulut dan kuku. Karantina segera dan batasi keluar masuk orang dan kendaraan.',
                'saran_sekarang' => ['Karantina sapi sakit dan batasi keluar masuk orang, kendaraan, dan peralatan', 'Laporkan ke dinas peternakan atau petugas kesehatan hewan', 'Jangan menjual atau memindahkan sapi'],
                'saran_perawatan' => ['Beri pakan lunak dan air bersih', 'Jaga lantai kandang bersih dan kering untuk melindungi kuku', 'Pengobatan pendukung, perendaman kaki, pereda nyeri, dan antibiotik untuk infeksi sekunder hanya atas arahan dokter hewan'],
                'saran_pencegahan' => ['Ikuti program vaksinasi PMK dari pemerintah', 'Disinfeksi kandang, kendaraan, dan sepatu', 'Karantina sapi baru sebelum digabung dengan kawanan'],
                'aturan' => ['blisters on mouth' => .8, 'blisters on tongue' => .8, 'blisters on gums' => .8, 'sores on mouth' => .8, 'sores on tongue' => .8, 'sores on gums' => .8, 'blisters on hooves' => .8, 'sores on hooves' => .8, 'lameness' => .5, 'difficulty walking' => .5, 'loss of appetite' => .4],
            ],
            [
                'kode' => 'lsd', 'nama' => 'Lumpy Skin Disease', 'status' => 'Ditularkan serangga', 'wajib_lapor' => true,
                'deskripsi' => 'Infeksi virus dengan benjolan keras di kulit. Kendalikan lalat dan nyamuk, dan pisahkan sapi sakit.',
                'saran_sekarang' => ['Pisahkan sapi sakit dari kawanan', 'Laporkan ke dinas peternakan', 'Batasi pergerakan sapi dari dan ke peternakan'],
                'saran_perawatan' => ['Sediakan pakan, air, dan tempat teduh yang mudah dijangkau', 'Perawatan luka kulit, pereda nyeri, dan antibiotik untuk komplikasi hanya atas arahan dokter hewan', 'Pantau nafsu makan, cara berjalan, dan jumlah benjolan'],
                'saran_pencegahan' => ['Kendalikan lalat, nyamuk, dan caplak di sekitar kandang', 'Ikuti program vaksinasi LSD', 'Jaga kebersihan kandang dan peralatan'],
                'aturan' => ['painless lumps' => .95, 'depression' => .4, 'loss of appetite' => .4],
            ],
            [
                'kode' => 'pneumonia', 'nama' => 'Pneumonia', 'status' => 'Radang paru', 'wajib_lapor' => false,
                'deskripsi' => 'Radang paru dengan sesak napas dan lesu. Jaga ventilasi kandang dan konsultasikan pengobatan ke dokter hewan.',
                'saran_sekarang' => ['Pisahkan sapi yang batuk atau sesak napas', 'Hubungi dokter hewan untuk pemeriksaan'],
                'saran_perawatan' => ['Tempatkan di kandang berventilasi baik, kering, dan tidak pengap', 'Sediakan air minum bersih dan pakan yang mudah dimakan', 'Antibiotik dan obat lain hanya atas resep dokter hewan'],
                'saran_pencegahan' => ['Kurangi kepadatan kandang dan stres pada sapi', 'Jaga kebersihan dan ventilasi kandang', 'Vaksinasi sesuai anjuran dokter hewan'],
                'aturan' => ['shortness of breath' => .6, 'chest discomfort' => .6, 'depression' => .5, 'loss of appetite' => .5, 'fatigue' => .4],
            ],
        ];

        $idGejala = Gejala::pluck('id', 'kode');

        foreach ($penyakit as $data) {
            $aturan = $data['aturan'];
            unset($data['aturan']);

            $p = Penyakit::updateOrCreate(['kode' => $data['kode']], $data);

            foreach ($aturan as $kodeGejala => $cf) {
                Aturan::updateOrCreate(
                    ['penyakit_id' => $p->id, 'gejala_id' => $idGejala[$kodeGejala]],
                    ['cf_pakar' => $cf]
                );
            }
        }
    }
}
