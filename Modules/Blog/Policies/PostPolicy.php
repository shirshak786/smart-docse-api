<?php

namespace Modules\Blog\Policies;

use Modules\Blog\Models\Post;
use Modules\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    public function view(User $authenticatedUser, Post $post)
    {
        if ($authenticatedUser->can('view posts')) {
            return true;
        }

        if ($authenticatedUser->can('view own posts')) {
            return $authenticatedUser->id === $post->user_id;
        }

        return false;
    }


    public function update(User $authenticatedUser, Post $post)
    {
        if ($authenticatedUser->can('edit posts')) {
            return true;
        }

        if ($authenticatedUser->can('edit own posts')) {
            return $authenticatedUser->id === $post->user_id;
        }

        return false;
    }

    public function delete(User $authenticatedUser, Post $post)
    {
        if ($authenticatedUser->can('delete posts')) {
            return true;
        }

        if ($authenticatedUser->can('delete own posts')) {
            return $authenticatedUser->id === $post->user_id;
        }

        return false;
    }
}
