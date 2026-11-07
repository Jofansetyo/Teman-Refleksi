<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        // Parafrase sendiri. Untuk penelitian resmi, pakai versi Indonesia PSS-10 yang tervalidasi.
        $item = [
            ['kesal karena sesuatu yang terjadi tak terduga', 0],
            ['tidak bisa mengendalikan hal-hal penting dalam hidupmu', 0],
            ['gelisah dan tertekan', 0],
            ['yakin mampu menangani masalah pribadimu', 1],
            ['segala sesuatu berjalan sesuai keinginanmu', 1],
            ['tidak sanggup menyelesaikan semua hal yang harus dikerjakan', 0],
            ['mampu mengendalikan rasa kesalmu', 1],
            ['berada di atas segala urusanmu', 1],
            ['marah karena hal-hal di luar kendalimu', 0],
            ['masalah menumpuk begitu tinggi sampai tak bisa diatasi', 0],
        ];
        foreach ($item as $n => [$teks, $balik]) {
            Question::updateOrCreate(
                ['kode' => sprintf('pss%02d', $n + 1)],
                ['teks' => $teks, 'tipe' => 'pss', 'dibalik' => (bool) $balik, 'urutan' => $n + 1, 'aktif' => true]
            );
        }
    }
}
