<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $t) {
            $t->id();
            $t->string('kode')->unique();           // contoh: pss01
            $t->text('teks');                       // lanjutan dari "Seberapa sering kamu merasa ..."
            $t->string('tipe')->default('pss');     // pss | gaya_hidup
            $t->boolean('dibalik')->default(false); // skor dibalik (4 - nilai)
            $t->unsignedSmallInteger('urutan')->default(0);
            $t->boolean('aktif')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
