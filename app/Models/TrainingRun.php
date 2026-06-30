<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingRun extends Model
{
    protected $fillable = [
        'training_experiment_id', 'status',
        'started_at', 'finished_at', 'logs', 'metrics',
    ];

    protected $casts = [
        'metrics'     => 'array',
        'started_at'  => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function experiment()
    {
        return $this->belongsTo(TrainingExperiment::class, 'training_experiment_id');
    }
}
