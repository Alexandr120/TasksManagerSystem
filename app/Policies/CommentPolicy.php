<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    /**
     * Determine if the given comments can be deleted by the user.
     *
     * @param  User  $user
     * @param  Comment $comment
     * @return bool
     */
    public function delete(User $user, Comment $comment): bool
    {
        if (!$user->hasRole('manager')) {
            return $user->hasPermissionTo('delete comment', 'api') && $user->id === $comment->user_id;
        }

        return $user->hasPermissionTo('delete comment', 'api');
    }
}
