@extends('layouts.app')

@section('content')
<section class="view" x-data="tes(@js($questions))" x-cloak>
    <div class="band"></div>
    <div class="wrap">
        <div class="top">
            <button type="button" class="link" @click="kembali()">← Kembali</button>
            <span x-text="'Pertanyaan ' + (i + 1) + ' dari ' + qs.length"></span>
        </div>

        <div class="track">
            <div class="line"><i :style="`width:${p * 100}%`"></i></div>
            <svg class="sail" :style="`left:calc(${p} * (100% - 44px))`" aria-hidden="true"><use href="#boat"/></svg>
            <span class="isl">Tujuan</span>
        </div>

        <template x-for="item in [q]" :key="item.id">
            <div class="qwrap">
                <p class="ql">Dalam sebulan terakhir</p>
                <h2 x-text="item.teks"></h2>
                <template x-for="(label, n) in opsi" :key="n">
                    <button type="button" class="opt" x-text="label" @click="pilih(n)" :disabled="memproses"></button>
                </template>
            </div>
        </template>

        {{-- Dikirim ke server setelah pertanyaan terakhir. Skor dihitung di server. --}}
        <form x-ref="form" method="POST" action="{{ route('tes.simpan') }}">
            @csrf
            <input type="hidden" name="consent_token" value="{{ session('tes_consent_token') }}">
            <div class="trap" aria-hidden="true">
                <label for="website">Website</label>
                <input id="website" type="text" name="website" tabindex="-1" autocomplete="off">
            </div>
            <template x-for="(nilai, id) in jawaban" :key="id">
                <input type="hidden" :name="`jawaban[${id}]`" :value="nilai">
            </template>
        </form>
    </div>
</section>
@endsection
