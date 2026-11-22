<?php

namespace App\Http\Controllers;

use App\Models\Diagnosa;
use App\Models\Gejala;
use App\Models\KasusDataset;
use App\Models\Penyakit;
use App\Services\CertaintyFactor;
use Illuminate\Http\Request;

class DiagnosaController extends Controller
{
    private function fotoLsd()
    {
        return collect(glob(public_path('images/lsd/*.jpg')) ?: [])
            ->map(fn ($f) => asset('images/lsd/' . basename($f)))->values();
    }

    public function beranda()
    {
        return view('beranda', [
            'kelompok' => Gejala::orderBy('urutan')->get()->groupBy('kelompok'),
            'penyakit' => Penyakit::with('aturan.gejala')->withCount('kasus')->get(),
            'totalKasus' => KasusDataset::count(),
            'totalGejala' => Gejala::count(),
            'fotoLsd' => $this->fotoLsd(),
            'pilihan' => CertaintyFactor::PILIHAN,
        ]);
    }

    public function proses(Request $request, CertaintyFactor $cf)
    {
        $data = $request->validate([
            'gejala' => ['required', 'array'],
            'gejala.*' => ['required', 'in:0,0.4,0.6,0.8,1'],
        ]);

        $masukan = array_filter(array_map('floatval', $data['gejala']), fn ($v) => $v > 0);

        if (! $masukan) {
            return back()->withErrors(['gejala' => 'Pilih minimal satu gejala yang terlihat pada sapi.']);
        }

        $teratas = $cf->hitung($masukan)->first();

        $diagnosa = Diagnosa::create([
            'penyakit_id' => $teratas['persen'] > 0 ? $teratas['penyakit']->id : null,
            'persen' => $teratas['persen'],
            'masukan' => $masukan,
        ]);

        return redirect()->route('diagnosa.hasil', $diagnosa);
    }

    public function hasil(Diagnosa $diagnosa, CertaintyFactor $cf)
    {
        return view('hasil', [
            'diagnosa' => $diagnosa,
            'hasil' => $cf->hitung($diagnosa->masukan),
            'jumlahGejala' => count($diagnosa->masukan),
            'fotoLsd' => $this->fotoLsd(),
        ]);
    }

    public function riwayat()
    {
        return view('riwayat', [
            'daftar' => Diagnosa::with('penyakit')->latest()->paginate(15),
        ]);
    }
}
