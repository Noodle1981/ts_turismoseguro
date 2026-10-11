<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Property;
use App\Models\User;

class PropertyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'admin', 'supervisor', 'client']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Property $property): bool
    {
        if (in_array($user->role, ['super_admin', 'admin', 'supervisor'])) {
            return true;
        }

        if ($user->role === 'validator' && $property->validator_id === $user->id) {
            return true;
        }

        return $user->id === $property->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['client', 'admin', 'super_admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Property $property): bool
    {
        if (in_array($user->role, ['super_admin', 'admin'])) {
            return true;
        }

        return $user->id === $property->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Property $property): bool
    {
        if (in_array($user->role, ['super_admin', 'admin'])) {
            return true;
        }

        return $user->id === $property->user_id;
    }
}
