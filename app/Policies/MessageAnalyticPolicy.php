<?php

namespace App\Policies;

use App\Models\MessageAnalytic;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MessageAnalyticPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo("viewAny message_analytics");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MessageAnalytic $messageAnalytic): bool
    {
        return $user->hasPermissionTo("view message_analytics");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo("create message_analytics");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MessageAnalytic $messageAnalytic): bool
    {
        return $user->hasPermissionTo("update message_analytics");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MessageAnalytic $messageAnalytic): bool
    {
        return $user->hasPermissionTo("delete message_analytics");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MessageAnalytic $messageAnalytic): bool
    {
        return $user->hasPermissionTo("restore message_analytics");
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MessageAnalytic $messageAnalytic): bool
    {
        return $user->hasPermissionTo("forceDelete message_analytics");
    }
}
