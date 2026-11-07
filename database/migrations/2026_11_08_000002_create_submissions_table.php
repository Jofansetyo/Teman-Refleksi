<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Anonim: tanpa nama, NIM, kontak, atau alamat IP.
        Schema::create('submissions', function (Blueprint $t) {
            $t->id();
            $t->uuid('uuid')->unique();
            $t->unsignedTinyInteger('skor');
            $t->string('kategori');                 // rendah | sedang | tinggi
            $t->boolean('consent')->default(false);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
