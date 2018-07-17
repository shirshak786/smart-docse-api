<?php

namespace Modules\News\Policies;

use Modules\News\Models\News;
use Modules\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NewsPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->is_admin) {
            return true;
        }
    }

    public function show(User $user)
    {
        return true;
    }

    public function store(User $user)
    {
        return true;
    }

    public function update(User $user, News $news)
    {
        return $news->author_id === $user->id;
    }

    public function delete(User $user, News $news)
    {
        return $news->author_id === $user->id;
    }
}
