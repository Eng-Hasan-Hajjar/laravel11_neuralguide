<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    protected $fillable = [
        'user_id',
        'problem_text',
        'detected_domain',
        'input_language',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function architectures()
    {
        return $this->belongsToMany(Architecture::class)
            ->withPivot(['score', 'rank', 'reason'])
            ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}