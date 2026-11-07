<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\Submission;
use App\Services\PssScorer;
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

        $response = $this->withSession(['tes_consent_token' => 'valid-token'])
            ->post(route('tes.simpan'), [
                'consent_token' => 'valid-token',
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

        $this->withSession(['tes_consent_token' => 'valid-token'])
            ->from(route('tes', ['consent' => 1]))
            ->post(route('tes.simpan'), [
                'consent_token' => 'valid-token',
                'jawaban' => [$question->id => 0, 999999 => 4],
            ])
            ->assertSessionHasErrors('jawaban');

        $this->assertDatabaseCount('submissions', 0);
    }

    public function test_submission_requires_a_valid_one_time_consent_token(): void
    {
        $question = Question::create([
            'kode' => 'pss01',
            'teks' => 'gelisah dan tertekan',
            'tipe' => 'pss',
            'urutan' => 1,
        ]);

        $this->post(route('tes.simpan'), [
            'consent_token' => 'missing-token',
            'jawaban' => [$question->id => 0],
        ])->assertSessionHasErrors('consent_token');
    }

    public function test_submission_rejects_a_filled_honeypot(): void
    {
        $question = Question::create([
            'kode' => 'pss01',
            'teks' => 'gelisah dan tertekan',
            'tipe' => 'pss',
            'urutan' => 1,
        ]);

        $this->withSession(['tes_consent_token' => 'valid-token'])
            ->post(route('tes.simpan'), [
                'consent_token' => 'valid-token',
                'website' => 'https://spam.example',
                'jawaban' => [$question->id => 0],
            ])
            ->assertSessionHasErrors('website');
    }

    public function test_test_page_returns_service_unavailable_without_active_questions(): void
    {
        $this->get(route('tes', ['consent' => 1]))->assertServiceUnavailable();
    }

    public function test_score_categories_cover_their_boundaries(): void
    {
        $this->assertSame('rendah', PssScorer::kategori(13)['kunci']);
        $this->assertSame('sedang', PssScorer::kategori(14)['kunci']);
        $this->assertSame('sedang', PssScorer::kategori(26)['kunci']);
        $this->assertSame('tinggi', PssScorer::kategori(27)['kunci']);
    }

    public function test_result_page_requires_session_binding(): void
    {
        $submission = Submission::create([
            'uuid' => 'b608cc87-157e-4ee8-8ba2-95f8e647f95c',
            'skor' => 14,
            'kategori' => 'sedang',
            'consent' => true,
        ]);

        // Accessing result without session binding should fail.
        $this->get(route('hasil', $submission))->assertForbidden();

        // Accessing result with correct session binding should succeed.
        $this->withSession(['tes_hasil_uuid' => $submission->uuid])
            ->get(route('hasil', $submission))
            ->assertOk()
            ->assertSee('Hasil refleksimu');
    }

    public function test_submission_rate_limit_allows_only_five_requests_per_minute(): void
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->withSession(['tes_consent_token' => "token-{$attempt}"])
                ->post(route('tes.simpan'), [
                    'consent_token' => "token-{$attempt}",
                    'jawaban' => [],
                ]);
        }

        $this->post(route('tes.simpan'), [
            'consent_token' => 'sixth-token',
            'jawaban' => [],
        ])->assertTooManyRequests();
    }
}
