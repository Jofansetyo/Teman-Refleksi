@extends('layouts.app')

@section('content')
<section class="view home">
    <div class="sky">
        <div class="sun"></div><div class="cloud c1"></div><div class="cloud c2"></div>
        <div class="brand">Teman Refleksi</div>
        <div class="hero">
            <p class="tag">SKRINING STRES MANDIRI</p>
            <h1>Seberapa lepas kamu hari ini?</h1>
            <p class="lead">Jawab sepuluh pertanyaan singkat untuk melihat seberapa berat tekanan yang kamu rasakan akhir-akhir ini.</p>
        </div>
        <div class="waves" aria-hidden="true">
            <svg class="w w1" viewBox="0 0 1200 100" preserveAspectRatio="none"><path d="M0 50C150 20 450 80 600 50C750 20 1050 80 1200 50V100H0Z" fill="var(--sea2)"/></svg>
            <svg class="w w2" viewBox="0 0 1200 100" preserveAspectRatio="none"><path d="M0 70C150 45 450 95 600 70C750 45 1050 95 1200 70V100H0Z" fill="var(--sea)"/></svg>
        </div>
        <svg class="boat" aria-hidden="true"><use href="#boat"/></svg>
    </div>
    <div class="sea">
        <form class="box" method="GET" action="{{ route('tes') }}" x-data="{ ok: false }">
            <p>Hasilnya untuk berefleksi, bukan mendiagnosis. Ini bukan instrumen PSS-10 tervalidasi dan tidak perlu nama, NIM, atau nomor HP.</p>
            <div class="note">Ini alat refleksi diri, bukan pengganti psikolog atau psikiater.
                <label>
                    <input type="checkbox" name="consent" value="1" x-model="ok">
                    <span>Saya mengerti dan setuju jawaban saya dipakai secara anonim.</span>
                </label>
            </div>
            <button type="submit" class="go" :disabled="!ok">Mulai, sekitar 3 menit</button>
        </form>
    </div>
</section>
@endsection
