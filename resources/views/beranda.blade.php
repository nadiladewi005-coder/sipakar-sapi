@extends('layouts.app')

@section('isi')
{{-- ============ HERO ============ --}}
<header class="bg-hero relative overflow-hidden py-16 text-[#eef7f2] sm:py-20">
    <div class="blob absolute -right-16 -top-28 size-96 rounded-full bg-hay-400 opacity-35 blur-3xl"></div>
    <div class="blob absolute -bottom-36 left-1/5 size-80 rounded-full bg-[#3fd0a8] opacity-40 blur-3xl" style="animation-delay:-5s"></div>

    <div class="relative mx-auto grid max-w-6xl items-center gap-10 px-5 md:grid-cols-[1.15fr_1fr]">
        <div>
            <h1 class="anim-up font-display text-4xl font-extrabold leading-[1.05] tracking-tight sm:text-6xl" style="--d:.1s">Sistem Pakar Diagnosis Penyakit Sapi</h1>
            <p class="anim-up mt-5 mb-7 max-w-[46ch] text-[#cde3da]" style="--d:.3s">Pilih gejala yang terlihat pada sapi, lihat foto contohnya agar tidak salah kenali, lalu dapatkan dugaan penyakit beserta tingkat keyakinannya.</p>
            <div class="anim-up flex flex-wrap gap-3" style="--d:.5s">
                <a href="#diagnosis" class="btn btn-warm">Mulai diagnosis</a>
                <a href="#metode" class="btn border-[1.5px] border-current">Cara hitungnya</a>
            </div>
            <div class="anim-up mt-9 flex flex-wrap gap-8" style="--d:.6s">
                <div><b class="block font-display text-3xl font-extrabold text-[#f7c766]">{{ $totalKasus ? number_format($totalKasus, 0, ',', '.') : '–' }}</b><span class="text-sm text-[#bbd6cb]">kasus sapi di database</span></div>
                <div><b class="block font-display text-3xl font-extrabold text-[#f7c766]">{{ $penyakit->count() }}</b><span class="text-sm text-[#bbd6cb]">penyakit</span></div>
                <div><b class="block font-display text-3xl font-extrabold text-[#f7c766]">{{ $totalGejala }}</b><span class="text-sm text-[#bbd6cb]">gejala</span></div>
            </div>
        </div>

        <div class="glass anim-up p-6" style="--d:.45s">
            <small class="text-[#bbd6cb]">Sebaran kasus sapi pada dataset</small>
            @if ($totalKasus)
                @php $maks = max(1, $penyakit->max('kasus_count')); @endphp
                @foreach ($penyakit->sortByDesc('kasus_count') as $p)
                    <div class="mt-4 text-sm font-semibold">
                        <div class="flex justify-between"><span>{{ $p->nama }}</span><span>{{ number_format($p->kasus_count, 0, ',', '.') }}</span></div>
                        <div class="mt-1 h-2.5 overflow-hidden rounded-full bg-white/20"><i class="mt-fill block h-full rounded-full" style="--w:{{ round($p->kasus_count / $maks * 100) }}%"></i></div>
                    </div>
                @endforeach
            @else
                <p class="mt-3 text-sm text-[#cde3da]">Dataset belum diimpor. Jalankan <code class="rounded bg-white/15 px-1.5">php artisan sipakar:import-dataset</code>.</p>
            @endif
        </div>
    </div>
</header>

{{-- ============ DIAGNOSIS ============ --}}
<section id="diagnosis" class="py-20">
    <div class="mx-auto max-w-6xl px-5">
        <h2 data-reveal class="judul font-display text-3xl font-bold tracking-tight sm:text-4xl">Gejala apa yang Anda lihat?</h2>
        <p data-reveal class="mt-4 mb-8 max-w-[62ch] text-muted">Untuk setiap gejala, pilih seberapa yakin Anda melihatnya. Gejala yang tidak terlihat biarkan pada “Tidak”.</p>

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-danger/40 bg-danger/10 px-4 py-3 text-danger">{{ $errors->first() }}</div>
        @endif

        <form id="form-diagnosa" method="POST" action="{{ route('diagnosa.proses') }}">
            @csrf

            @foreach ($kelompok as $nama => $daftar)
                <div data-reveal class="mt-9 mb-4 flex items-center gap-3 font-display text-xl font-bold after:h-px after:flex-1 after:bg-linear-to-r after:from-line after:to-transparent">{{ $nama }}</div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach ($daftar as $i => $g)
                        <div data-reveal data-card style="--d:{{ ($i % 4) * 80 }}ms" class="sym card overflow-hidden border-[1.5px]">
                            @if ($g->gambar_url)
                                <img src="{{ $g->gambar_url }}" alt="Contoh foto: {{ $g->nama }}" loading="lazy" class="aspect-[16/10] w-full object-cover">
                            @else
                                <div class="flex aspect-[16/10] items-center justify-center p-3 text-center text-sm font-semibold text-white" style="background:linear-gradient(135deg,{{ $g->warna_a }},{{ $g->warna_b }})">Foto belum tersedia</div>
                            @endif

                            <div class="p-3.5">
                                <h3 class="mb-2.5 font-display text-base font-bold">{{ $g->nama }}</h3>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach ($pilihan as $nilai => $label)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="gejala[{{ $g->id }}]" value="{{ $nilai }}" class="peer sr-only" @checked((string) old("gejala.{$g->id}", '0') === (string) $nilai)>
                                            <span class="block rounded-full border border-line px-2.5 py-1 text-xs font-medium transition hover:-translate-y-px peer-checked:border-transparent peer-checked:bg-brand peer-checked:text-white peer-focus-visible:outline-2 peer-focus-visible:outline-offset-2 peer-focus-visible:outline-hay-400 dark:border-white/15">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

            <div class="card sticky bottom-3 z-30 mt-8 flex items-center gap-3 px-4 py-3 shadow-xl backdrop-blur-md">
                <span id="cnt" class="mr-auto text-sm text-muted">0 gejala dipilih</span>
                <button type="reset" class="btn border-[1.5px] border-line py-2 text-sm dark:border-white/20">Reset</button>
                <button type="submit" class="btn btn-warm py-2">Diagnosis</button>
            </div>
        </form>
    </div>
</section>

{{-- ============ PENYAKIT ============ --}}
<section id="penyakit" class="py-20">
    <div class="mx-auto max-w-6xl px-5">
        <h2 data-reveal class="judul font-display text-3xl font-bold tracking-tight sm:text-4xl">Penyakit yang dikenali sistem</h2>
        <p data-reveal class="mt-4 mb-8 max-w-[62ch] text-muted">Lima penyakit dari dataset, dengan gejala, bobot keyakinan pakar (CF), dan seberapa sering gejala itu muncul pada kasus dataset.</p>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($penyakit as $i => $p)
                <article data-reveal style="--d:{{ $i * 90 }}ms" class="card relative overflow-hidden p-5 transition duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-brand-600/20">
                    <span class="absolute inset-x-0 top-0 h-1 bg-brand"></span>
                    <h3 class="font-display text-xl font-bold">{{ $p->nama }}</h3>
                    <small class="font-semibold text-danger">{{ $p->status }}</small>
                    <p class="my-2 text-sm text-muted">{{ $p->deskripsi }}</p>

                    @if ($p->kode === 'lsd' && $fotoLsd->isNotEmpty())
                        <div class="my-3 grid grid-cols-2 gap-2">
                            @foreach ($fotoLsd->take(4) as $foto)
                                <img src="{{ $foto }}" alt="Foto sapi lumpy skin disease" loading="lazy" class="aspect-square w-full rounded-lg object-cover">
                            @endforeach
                        </div>
                    @endif

                    <div class="flex flex-wrap gap-1.5">
                        @foreach ($p->aturan as $a)
                            <i class="rounded-md bg-linear-to-r from-hay-400/30 to-brand-600/20 px-2 py-0.5 text-xs not-italic">
                                {{ $a->gejala->nama }} (CF {{ number_format($a->cf_pakar, 2, ',', '.') }}@if (! is_null($a->frekuensi)), {{ round($a->frekuensi * 100) }}% kasus @endif)
                            </i>
                        @endforeach
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ============ METODE ============ --}}
<section id="metode" class="bg-linear-to-b from-transparent to-brand-600/10 py-20">
    <div class="mx-auto grid max-w-6xl items-start gap-10 px-5 md:grid-cols-2">
        <div data-reveal>
            <h2 class="judul font-display text-3xl font-bold tracking-tight sm:text-4xl">Cara Certainty Factor bekerja</h2>
            <p class="mt-4 mb-6 text-muted">Setiap gejala punya bobot dari pakar. Bobot itu dikalikan dengan keyakinan Anda, lalu semua gejala digabung menjadi satu nilai.</p>
            <div class="rounded-2xl bg-brand-700 bg-linear-to-br from-brand-900 to-brand-600 p-6 leading-loose text-[#eef7f2]">
                CF gejala = <b class="font-display text-hay-400">CF pakar × CF pengguna</b><br>
                CF gabungan = <b class="font-display text-hay-400">CF1 + CF2 × (1 − CF1)</b><br>
                Persentase = <b class="font-display text-hay-400">CF gabungan × 100%</b>
            </div>
        </div>
        <div data-reveal style="--d:150ms">
            <h3 class="mb-3 font-display text-xl font-bold">Pilihan keyakinan pengguna</h3>
            <div class="mb-6 flex flex-wrap gap-1.5 text-xs">
                @foreach ($pilihan as $nilai => $label)
                    <i class="rounded-md bg-linear-to-r from-hay-400/30 to-brand-600/20 px-2 py-0.5 not-italic">{{ $label }} = {{ str_replace('.', ',', $nilai) }}</i>
                @endforeach
            </div>
            <h3 class="mb-2 font-display text-xl font-bold">Tingkat hasil</h3>
            <p class="text-muted">Di atas 80% sangat mungkin, 60–80% mungkin, 40–60% perlu dicurigai, dan di bawah 40% kecil kemungkinannya.</p>
        </div>
    </div>
</section>

{{-- ============ WATERFALL ============ --}}
<section id="tahap" class="py-20">
    <div class="mx-auto max-w-6xl px-5">
        <h2 data-reveal class="judul font-display text-3xl font-bold tracking-tight sm:text-4xl">Tahapan pengembangan Waterfall</h2>
        <p data-reveal class="mt-4 mb-8 text-muted">Sistem ini dikembangkan berurutan, satu tahap selesai sebelum tahap berikutnya dimulai.</p>
        <ol class="timeline ml-2 border-l-2 border-line">
            @foreach ([
                ['Analisis kebutuhan', 'Mengumpulkan data gejala dan penyakit sapi dari dataset, pakar, dan literatur.'],
                ['Desain sistem', 'Merancang basis pengetahuan, aturan CF, alur diagnosis, dan tampilan web.'],
                ['Implementasi', 'Menulis kode Laravel, database, dan mesin inferensi Certainty Factor.'],
                ['Pengujian', 'Membandingkan hasil sistem dengan diagnosis pakar, juga uji fungsi dan kegunaan.'],
                ['Pemeliharaan', 'Memperbarui gejala, penyakit, dan bobot pakar sesuai masukan.'],
            ] as $i => [$judul, $isi])
                <li data-reveal style="--d:{{ $i * 100 }}ms" class="relative pb-6 pl-6">
                    <b class="font-display text-lg">{{ $judul }}</b>
                    <p class="text-muted">{{ $isi }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>
@endsection
