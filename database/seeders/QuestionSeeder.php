<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        // Pertanyaan refleksi, bukan adaptasi atau instrumen PSS-10 tervalidasi.
        $item = [
            ['Seberapa sering kamu merasa kesal karena sesuatu yang terjadi tak terduga?', 0],
            ['Seberapa sering kamu merasa tidak bisa mengendalikan hal-hal penting dalam hidupmu?', 0],
            ['Seberapa sering kamu merasa gelisah dan tertekan?', 0],
            ['Seberapa sering kamu merasa yakin mampu menangani masalah pribadimu?', 1],
            ['Seberapa sering kamu merasa segala sesuatu berjalan sesuai keinginanmu?', 1],
            ['Seberapa sering kamu merasa tidak sanggup menyelesaikan semua hal yang harus dikerjakan?', 0],
            ['Seberapa sering kamu mampu mengendalikan rasa kesalmu?', 1],
            ['Seberapa sering kamu merasa berada di atas segala urusan?', 1],
            ['Seberapa sering kamu merasa marah karena hal-hal di luar kendalimu?', 0],
            ['Seberapa sering kamu merasa masalah menumpuk begitu tinggi sampai tak bisa diatasi?', 0],
        ];
        foreach ($item as $n => [$teks, $balik]) {
            Question::updateOrCreate(
                ['kode' => sprintf('pss%02d', $n + 1)],
                ['teks' => $teks, 'tipe' => 'pss', 'dibalik' => (bool) $balik, 'urutan' => $n + 1, 'aktif' => true]
            );
        }
    }
}
