<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('answers', function (Blueprint $t) {
            $t->id();
            $t->foreignId('submission_id')->constrained()->cascadeOnDelete();
            $t->foreignId('question_id')->constrained();
            $t->unsignedTinyInteger('nilai');       // nilai mentah 0-4 (sebelum dibalik)
            $t->unique(['submission_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
