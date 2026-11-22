<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('judul', 'SiPakar Sapi') – Diagnosis Penyakit Sapi dengan Certainty Factor</title>
    <script>document.documentElement.classList.add('js')</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@500;700;800&family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="overflow-x-hidden bg-mist font-sans text-ink antialiased dark:bg-night dark:text-snow">

<nav class="sticky top-0 z-40 border-b border-line/70 bg-mist/80 backdrop-blur-md dark:border-white/10 dark:bg-night/80">
    <div class="mx-auto flex h-14 max-w-6xl items-center gap-6 px-5">
        <a href="{{ route('beranda') }}" class="font-display text-lg font-extrabold text-grad mr-auto">SiPakar Sapi</a>
        <a href="{{ route('beranda') }}#diagnosis" class="nav-link">Diagnosis</a>
        <a href="{{ route('beranda') }}#penyakit" class="nav-link">Penyakit</a>
        <a href="{{ route('beranda') }}#metode" class="nav-link hidden sm:inline">Metode</a>
        <a href="{{ route('riwayat') }}" class="nav-link">Riwayat</a>
    </div>
</nav>

@yield('isi')

<footer class="border-t border-line py-8 text-sm text-muted dark:border-white/10">
    <div class="mx-auto max-w-6xl space-y-2 px-5">
        <p>Sistem Pakar Diagnosis Penyakit Hewan Ternak Sapi Berdasarkan Gejala Menggunakan Metode Certainty Factor Berbasis Web Menggunakan Pendekatan Waterfall.</p>
        <p>Foto lumpy skin: Lumpy Skin Images Dataset, Mendeley Data, DOI 10.17632/w36hpf86j2.1. Data gejala dan penyakit: Livestock Symptoms and Diseases (Kaggle). Bobot CF adalah contoh awal dan perlu divalidasi pakar.</p>
    </div>
</footer>
</body>
</html>
