<?php

namespace Modules\Role\Repositories\Contracts;

use Modules\Core\Contracts\BaseRepository;
use Modules\Role\Models\Role;

interface RoleRepository extends BaseRepository
{
    public function store(array $input);

    public function update(Role $role, array $input);

    public function destroy(Role $role);

    public function getAllowedRoles();
}
