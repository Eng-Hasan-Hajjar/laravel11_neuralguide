<?php

namespace App\Policies;

use App\Models\TrainingDataset;
use App\Models\User;

class TrainingDatasetPolicy
{
    public function view(User $user, TrainingDataset $dataset): bool
    {
        return $user->id === $dataset->user_id || $user->role === 'admin';
    }

    public function delete(User $user, TrainingDataset $dataset): bool
    {
        return $user->id === $dataset->user_id || $user->role === 'admin';
    }
}
