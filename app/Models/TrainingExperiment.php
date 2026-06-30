<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingExperiment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'architecture_id', 'dataset_id',
        'name', 'framework', 'hyperparameters',
        'generated_code', 'custom_code', 'notes',
        'status', 'result_metrics',
    ];

    protected $casts = [
        'hyperparameters' => 'array',
        'result_metrics'  => 'array',
    ];

    // ─── علاقات ───────────────────────────────────────────────
    public function user()         { return $this->belongsTo(User::class); }
    public function architecture() { return $this->belongsTo(Architecture::class); }
    public function dataset()      { return $this->belongsTo(TrainingDataset::class); }
    public function runs()         { return $this->hasMany(TrainingRun::class); }

    // ─── مساعدات ──────────────────────────────────────────────
    public function activeCode(): string
    {
        return $this->custom_code ?? $this->generated_code ?? '';
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'running'   => 'blue',
            'completed' => 'green',
            'failed'    => 'red',
            default     => 'gray',
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'draft'     => 'مسودة',
            'queued'    => 'في الانتظار',
            'running'   => 'يعمل',
            'completed' => 'مكتمل',
            'failed'    => 'فشل',
            default     => $this->status,
        };
    }
}
