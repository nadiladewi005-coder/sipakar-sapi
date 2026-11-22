@extends('layouts.app')

@section('judul', 'Riwayat diagnosis')

@section('isi')
<section class="py-16">
    <div class="mx-auto max-w-4xl px-5">
        <h1 class="judul font-display text-3xl font-bold tracking-tight sm:text-4xl">Riwayat diagnosis</h1>
        <p class="mt-4 mb-8 text-muted">Daftar diagnosis yang pernah dilakukan di sistem ini.</p>

        <div class="card overflow-x-auto">
            <table class="w-full min-w-[520px] text-left text-sm">
                <thead class="text-muted"><tr class="border-b border-line dark:border-white/10">
                    <th class="px-4 py-3">Waktu</th><th class="px-4">Dugaan penyakit</th><th class="px-4">Keyakinan</th><th class="px-4">Gejala</th><th></th>
                </tr></thead>
                <tbody>
                    @forelse ($daftar as $d)
                        <tr class="border-b border-line/60 transition hover:bg-brand-600/5 dark:border-white/5">
                            <td class="px-4 py-3">{{ $d->created_at->translatedFormat('j M Y, H:i') }}</td>
                            <td class="px-4">{{ $d->penyakit?->nama ?? 'Tidak ada yang cocok' }}</td>
                            <td class="px-4 font-semibold">{{ str_replace('.', ',', number_format($d->persen, 1)) }}%</td>
                            <td class="px-4">{{ count($d->masukan) }}</td>
                            <td class="px-4"><a class="font-semibold text-brand-600 hover:underline" href="{{ route('diagnosa.hasil', $d) }}">Lihat</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-muted">Belum ada diagnosis. <a class="font-semibold text-brand-600 hover:underline" href="{{ route('beranda') }}#diagnosis">Mulai diagnosis</a>.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $daftar->links() }}</div>
    </div>
</section>
@endsection
