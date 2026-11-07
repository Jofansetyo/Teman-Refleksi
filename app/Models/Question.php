<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['kode', 'teks', 'tipe', 'dibalik', 'urutan', 'aktif'];

    protected $casts = ['dibalik' => 'boolean', 'aktif' => 'boolean'];

    public function scopePss($q)
    {
        return $q->where('tipe', 'pss')->where('aktif', true)->orderBy('urutan');
    }
}
