@extends('layouts.app')

@section('judul', 'Hasil diagnosis')

@php
    $top = $hasil->first();
    $p = $top['penyakit'];
    $angka = fn ($x, $d = 2) => number_format($x, $d, ',', '.');
@endphp

@section('isi')
<section class="bg-linear-to-b from-brand-600/10 to-transparent py-16">
    <div class="mx-auto max-w-6xl px-5">
        <h1 class="judul font-display text-3xl font-bold tracking-tight sm:text-4xl">Hasil diagnosis</h1>
        <p class="mt-4 mb-8 text-muted">{{ $jumlahGejala }} gejala dipilih pada {{ $diagnosa->created_at->translatedFormat('j F Y, H:i') }}. Hasil diurutkan dari keyakinan tertinggi.</p>

        <div class="grid items-start gap-7 md:grid-cols-2">
            {{-- penyakit teratas --}}
            <div class="anim-pop rounded-3xl bg-linear-to-br from-brand-900 via-brand-600 to-brand-500 p-7 text-[#eef7f2] shadow-2xl shadow-brand-900/30">
                @if ($top['persen'] > 0)
                    <div data-count="{{ $top['persen'] }}" class="text-grad-warm font-display text-7xl font-extrabold leading-none">0,0%</div>
                    <h2 class="mt-2 mb-2 font-display text-2xl font-bold">{{ $p->nama }}</h2>
                    <p class="mb-2 text-[#d3e7de]"><b>{{ $top['tingkat'] }}</b>. Cocok dengan gejala: {{ collect($top['langkah'])->pluck('gejala')->map(fn ($g) => mb_strtolower($g))->join(', ') }}.</p>
                    <p class="text-[#d3e7de]">{{ $p->deskripsi }}</p>
                @else
                    <h2 class="font-display text-2xl font-bold">Tidak ada yang cocok</h2>
                    <p class="mt-2 text-[#d3e7de]">Gejala yang dipilih tidak sesuai dengan penyakit di basis pengetahuan.</p>
                @endif
            </div>

            {{-- semua penyakit --}}
            <div>
                @foreach ($hasil as $r)
                    <div class="mb-4">
                        <div class="flex justify-between font-semibold"><span>{{ $r['penyakit']->nama }}</span><span>{{ str_replace('.', ',', number_format($r['persen'], 1)) }}%</span></div>
                        <div class="mt-1.5 h-3 overflow-hidden rounded-full bg-line dark:bg-white/10"><div class="fill h-full rounded-full" data-w="{{ $r['persen'] }}"></div></div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- saran penanganan --}}
        @if ($top['persen'] > 0)
            <div class="anim-pop card relative mt-8 overflow-hidden p-7 shadow-xl" style="--d:.25s">
                <span class="absolute inset-x-0 top-0 h-1.5 bg-warm"></span>
                <h2 class="mb-3 font-display text-2xl font-bold">Saran penanganan: {{ $p->nama }}</h2>
                @if ($p->wajib_lapor)
                    <div class="bg-alert mb-4 inline-block rounded-full px-3.5 py-1 text-sm font-semibold text-white">Wajib dilaporkan ke dinas peternakan</div>
                @endif
                <div class="grid gap-6 md:grid-cols-3">
                    @foreach ([['Lakukan sekarang', $p->saran_sekarang], ['Perawatan dan pemantauan', $p->saran_perawatan], ['Pencegahan', $p->saran_pencegahan]] as [$judul, $daftar])
                        <div>
                            <h3 class="mb-2 font-display text-base font-bold text-brand-600 dark:text-brand-500">{{ $judul }}</h3>
                            <ul class="list-disc space-y-1.5 pl-5 text-[15px]">
                                @foreach ($daftar ?? [] as $s)<li>{{ $s }}</li>@endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>

                @if ($p->kode === 'lsd' && $fotoLsd->isNotEmpty())
                    <div class="mt-6 grid grid-cols-2 gap-2 sm:grid-cols-4">
                        @foreach ($fotoLsd->take(4) as $foto)
                            <img src="{{ $foto }}" alt="Contoh sapi lumpy skin disease" loading="lazy" class="aspect-square w-full rounded-lg object-cover">
                        @endforeach
                    </div>
                @endif

                <p class="mt-5 text-sm text-muted">Acuan awal: Pedoman Biosekuriti Ruminansia Besar (Kementan) dan lembar kerja PMK dan LSD (Ausvet). Ini saran umum tanpa nama obat dan dosis. Penanganan pasti ditentukan dokter hewan.</p>
            </div>
        @endif

        {{-- perhitungan --}}
        <h2 class="judul mt-14 mb-5 font-display text-2xl font-bold">Perhitungan Certainty Factor</h2>
        @foreach ($hasil->filter(fn ($r) => $r['langkah']) as $i => $r)
            <details class="card mb-3 p-4" @if ($loop->first) open @endif>
                <summary class="cursor-pointer font-semibold">{{ $r['penyakit']->nama }} — {{ str_replace('.', ',', number_format($r['persen'], 1)) }}%</summary>
                <div class="mt-3 overflow-x-auto">
                    <table class="w-full min-w-[560px] text-left text-sm">
                        <thead class="text-muted"><tr class="border-b border-line dark:border-white/10">
                            <th class="py-2 pr-3">Gejala</th><th class="pr-3">CF pakar</th><th class="pr-3">CF pengguna</th><th class="pr-3">CF gejala<br><span class="font-normal">(pakar × pengguna)</span></th><th>CF gabungan</th>
                        </tr></thead>
                        <tbody>
                            @foreach ($r['langkah'] as $l)
                                <tr class="border-b border-line/60 dark:border-white/5">
                                    <td class="py-2 pr-3">{{ $l['gejala'] }}</td>
                                    <td class="pr-3">{{ $angka($l['cf_pakar']) }}</td>
                                    <td class="pr-3">{{ $angka($l['cf_pengguna']) }}</td>
                                    <td class="pr-3">{{ $angka($l['cf_gejala'], 3) }}</td>
                                    <td>
                                        @if ($loop->first) {{ $angka($l['cf_gabungan'], 3) }}
                                        @else {{ $angka($l['cf_lama'], 3) }} + {{ $angka($l['cf_gejala'], 3) }} × (1 − {{ $angka($l['cf_lama'], 3) }}) = <b>{{ $angka($l['cf_gabungan'], 3) }}</b>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="mt-3 text-sm">Persentase akhir = {{ $angka($r['cf'], 3) }} × 100% = <b>{{ str_replace('.', ',', number_format($r['persen'], 1)) }}%</b></p>
            </details>
        @endforeach

        <p class="mt-8 border-l-[3px] border-danger pl-3 text-sm text-muted">Hasil ini hanya dugaan awal dari sistem pakar. Segera hubungi dokter hewan atau petugas dinas peternakan, dan pisahkan sapi yang sakit dari kawanan.</p>
        <a href="{{ route('beranda') }}#diagnosis" class="btn btn-warm mt-8">Diagnosis lagi</a>
    </div>
</section>
@endsection
