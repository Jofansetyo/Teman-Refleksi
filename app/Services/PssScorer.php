<?php

namespace App\Services;

use Illuminate\Support\Collection;

class PssScorer
{
    /** Skor PSS-10 (0-40). Item bertanda dibalik dihitung 4 - nilai. */
    public static function skor(Collection $pertanyaan, array $jawaban): int
    {
        return (int) $pertanyaan->sum(function ($q) use ($jawaban) {
            $n = (int) $jawaban[$q->id];
            return $q->dibalik ? 4 - $n : $n;
        });
    }

    /** Batas umum: 0-13 rendah, 14-26 sedang, 27-40 tinggi. Bukan diagnosis. */
    public static function kategori(int $skor): array
    {
        if ($skor <= 13) {
            return ['kunci' => 'rendah', 'label' => 'Rendah', 'bg' => '#2A9D8F', 'fg' => '#0A2E40',
                'pesan' => 'Tekananmu tergolong ringan. Pertahankan kebiasaan yang membuatmu merasa baik.',
                'tips' => ['Jaga jam tidur yang teratur.', 'Sisihkan waktu untuk hal yang kamu suka.']];
        }
        if ($skor <= 26) {
            return ['kunci' => 'sedang', 'label' => 'Sedang', 'bg' => '#E8B04B', 'fg' => '#0A2E40',
                'pesan' => 'Kamu sedang merasakan tekanan yang cukup terasa. Ini wajar dan bisa dikelola dengan beberapa penyesuaian.',
                'tips' => ['Pecah pekerjaan besar jadi bagian kecil.', 'Coba jalan kaki 15 menit atau tarik napas dalam beberapa kali.', 'Ceritakan isi pikiranmu ke satu orang yang kamu percaya.']];
        }
        return ['kunci' => 'tinggi', 'label' => 'Tinggi', 'bg' => '#C24A33', 'fg' => '#ffffff',
            'pesan' => 'Tekananmu terasa berat. Kamu tidak perlu menghadapinya sendirian.',
            'tips' => ['Bicaralah dengan orang terdekat hari ini.', 'Pertimbangkan konseling kampus atau psikolog.', 'Istirahat sejenak, satu langkah kecil sudah cukup.']];
    }
}
