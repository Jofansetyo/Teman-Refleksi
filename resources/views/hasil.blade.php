@extends('layouts.app')

@section('content')
<section class="view" x-data="hasil({{ $skor }})">
    <div class="band"></div>
    <div class="wrap">
        <h2 style="margin-top:24px">Hasil refleksimu</h2>
        <div class="card">
            <div>
                <span class="score" x-text="tampil">{{ $skor }}</span>
                <span style="color:var(--mute)">dari 40</span>
                <span class="pill" style="background:{{ $info['bg'] }};color:{{ $info['fg'] }}">{{ $info['label'] }}</span>
            </div>
            <div class="gauge"><b :style="`left:${pos}%`" style="left:0"></b></div>
            <div class="sc"><span>0</span><span>13</span><span>26</span><span>40</span></div>
            <p style="margin:18px 0 0">{{ $info['pesan'] }}</p>
        </div>

        <div class="card rise" style="animation-delay:.9s">
            <b>Langkah kecil untukmu</b>
            <ul>@foreach ($info['tips'] as $t)<li>{{ $t }}</li>@endforeach</ul>
        </div>

        <p class="fine rise" style="animation-delay:1.1s">
            Butuh bicara dengan seseorang? Hubungi konseling kampus atau layanan kesehatan jiwa Kemenkes di 119 ekstensi 8.
            Skor dihitung dengan PSS-10 dan bukan diagnosis medis.
        </p>
        <a class="out" href="{{ route('beranda') }}">Ulangi</a>
    </div>
</section>
@endsection
