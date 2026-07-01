<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'bio', 'affiliation',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ─── Helpers ──────────────────────────────────────────────
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // ─── Relations ────────────────────────────────────────────
    public function suggestions()
    {
        return $this->hasMany(Suggestion::class);
    }

    public function researchNotes()
    {
        return $this->hasMany(ResearchNote::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Architecture::class, 'favorites')
                    ->withTimestamps();
    }

    public function experiments()
    {
        return $this->hasMany(\App\Models\TrainingExperiment::class);
    }

    public function datasets()
    {
        return $this->hasMany(\App\Models\TrainingDataset::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
