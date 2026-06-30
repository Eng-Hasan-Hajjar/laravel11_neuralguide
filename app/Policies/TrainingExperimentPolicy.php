<?php

namespace App\Policies;

use App\Models\TrainingExperiment;
use App\Models\User;

class TrainingExperimentPolicy
{
    public function view(User $user, TrainingExperiment $experiment): bool
    {
        return $user->id === $experiment->user_id || $user->role === 'admin';
    }

    public function update(User $user, TrainingExperiment $experiment): bool
    {
        return $user->id === $experiment->user_id || $user->role === 'admin';
    }

    public function delete(User $user, TrainingExperiment $experiment): bool
    {
        return $user->id === $experiment->user_id || $user->role === 'admin';
    }
}
