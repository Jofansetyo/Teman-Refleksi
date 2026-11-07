<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Submission;
use App\Services\PssScorer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TesController extends Controller
{
    public function beranda()
    {
        return view('beranda');
    }

    public function tes(Request $request)
    {
        // Persetujuan datang dari kotak centang di Beranda.
        if (! $request->boolean('consent')) {
            return redirect()->route('beranda');
        }
        $questions = Question::pss()->get(['id', 'teks']);

        if ($questions->isEmpty()) {
            abort(503, 'Tes belum tersedia.');
        }

        return view('tes', compact('questions'));
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'consent' => ['accepted'],
            'jawaban' => ['required', 'array'],
            'jawaban.*' => ['integer', 'between:0,4'],
        ]);

        $pertanyaan = Question::pss()->get();
        $jawaban = $request->input('jawaban');

        // Semua pertanyaan aktif harus terjawab, tanpa jawaban tambahan.
        $questionIds = $pertanyaan->pluck('id')->map(static fn ($id) => (string) $id)->all();
        $answerIds = array_map('strval', array_keys($jawaban));

        if ($pertanyaan->isEmpty() || array_diff($questionIds, $answerIds) || array_diff($answerIds, $questionIds)) {
            throw ValidationException::withMessages([
                'jawaban' => 'Jawab semua pertanyaan sebelum mengirim tes.',
            ]);
        }

        $skor = PssScorer::skor($pertanyaan, $jawaban);
        $info = PssScorer::kategori($skor);

        $submission = DB::transaction(function () use ($pertanyaan, $jawaban, $skor, $info) {
            $s = Submission::create([
                'uuid' => (string) Str::uuid(),
                'skor' => $skor,
                'kategori' => $info['kunci'],
                'consent' => true,
            ]);
            foreach ($pertanyaan as $q) {
                Answer::create(['submission_id' => $s->id, 'question_id' => $q->id, 'nilai' => (int) $jawaban[$q->id]]);
            }
            return $s;
        });

        return redirect()->route('hasil', $submission->uuid);
    }

    public function hasil(Submission $submission)
    {
        $info = PssScorer::kategori($submission->skor);
        return view('hasil', ['skor' => $submission->skor, 'info' => $info]);
    }
}
