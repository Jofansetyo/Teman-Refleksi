<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = ['uuid', 'skor', 'kategori', 'consent'];

    protected $casts = ['consent' => 'boolean'];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
