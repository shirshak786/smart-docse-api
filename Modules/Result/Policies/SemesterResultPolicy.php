<?php

namespace Modules\Result\Policies;

use Modules\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SemesterResultPolicy
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

    public function update(User $user)
    {
        return true;
    }

    public function delete(User $user)
    {
        return true;
    }
}
