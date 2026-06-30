<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingDataset extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'description', 'type',
        'task_type', 'file_path', 'file_size', 'file_name', 'meta',
    ];

    protected $casts = ['meta' => 'array'];

    public function user()        { return $this->belongsTo(User::class); }
    public function experiments() { return $this->hasMany(TrainingExperiment::class, 'dataset_id'); }

    public function formattedSize(): string
    {
        $bytes = $this->file_size ?? 0;
        if ($bytes < 1024)       return $bytes . ' B';
        if ($bytes < 1048576)    return round($bytes / 1024, 1) . ' KB';
        if ($bytes < 1073741824) return round($bytes / 1048576, 1) . ' MB';
        return round($bytes / 1073741824, 2) . ' GB';
    }
}
