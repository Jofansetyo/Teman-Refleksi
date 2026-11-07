<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_home_page_loads(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_the_test_page_requires_consent(): void
    {
        $this->get(route('tes'))->assertRedirect(route('beranda'));
    }

    public function test_a_complete_consenting_submission_is_scored_and_persisted(): void
    {
        $questions = collect(range(1, 10))->map(function (int $position): Question {
            return Question::create([
                'kode' => sprintf('pss%02d', $position),
                'tipe' => 'pss',
                'aktif' => true,
                'urutan' => $position,
                'dibalik' => in_array($position, [4, 5, 7, 8], true),
                'teks' => "pertanyaan {$position}",
            ]);
        });

        $answers = $questions->mapWithKeys(static fn (Question $question) => [$question->id => 2])->all();

        $response = $this->post(route('tes.simpan'), [
            'consent' => true,
            'jawaban' => $answers,
        ]);

        $submission = Submission::firstOrFail();

        $response->assertRedirect(route('hasil', $submission));
        $this->assertSame(20, $submission->skor);
        $this->assertSame('sedang', $submission->kategori);
        $this->assertTrue($submission->consent);
        $this->assertCount(10, $submission->answers);
    }

    public function test_submission_rejects_missing_or_extra_answers(): void
    {
        $question = Question::create([
            'kode' => 'pss01',
            'teks' => 'gelisah dan tertekan',
            'tipe' => 'pss',
            'urutan' => 1,
        ]);

        $this->from(route('tes', ['consent' => 1]))
            ->post(route('tes.simpan'), [
                'consent' => true,
                'jawaban' => [$question->id => 0, 999999 => 4],
            ])
            ->assertSessionHasErrors('jawaban');

        $this->assertDatabaseCount('submissions', 0);
    }
}
