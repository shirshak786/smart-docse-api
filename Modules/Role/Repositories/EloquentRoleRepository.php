<?php

namespace Modules\Role\Repositories;

use Modules\Role\Models\Role;
use Modules\Core\Exceptions\GeneralException;
use Modules\Core\Repositories\EloquentBaseRepository;
use Modules\Role\Repositories\Contracts\RoleRepository;

/**
 * Class EloquentRoleRepository.
 */
class EloquentRoleRepository extends EloquentBaseRepository implements RoleRepository
{
    public function __construct(Role $role)
    {
        parent::__construct($role);
    }

    public function store(array $input)
    {
        /** @var Role $role */
        $role = $this->make($input);

        if (! $this->save($role, $input)) {
            throw new GeneralException(__('exceptions.backend.roles.create'));
        }

        return $role;
    }

    public function update(Role $role, array $input)
    {
        $role->fill($input);

        if (! $this->save($role, $input)) {
            throw new GeneralException(__('exceptions.backend.roles.update'));
        }

        return $role;
    }

    private function save(Role $role, array $input)
    {
        if (! $role->save($input)) {
            return false;
        }

        $role->permissions()->delete();

        $permissions = $input['permissions'] ?? [];

        foreach ($permissions as $name) {
            $role->permissions()->create(['name' => $name]);
        }

        return true;
    }

    public function destroy(Role $role)
    {
        if (! $role->delete()) {
            throw new GeneralException(__('exceptions.backend.roles.delete'));
        }

        return true;
    }

    public function getAllowedRoles()
    {
        $authenticatedUser = auth()->user();

        if (! $authenticatedUser) {
            return [];
        }

        $roles = $this->query()->with('permissions')->orderBy('order')->get();

        if ($authenticatedUser->is_super_admin) {
            return $roles;
        }

        $permissions = $authenticatedUser->getPermissions();

        $roles = $roles->filter(function (Role $role) use ($permissions) {
            foreach ($role->permissions as $permission) {
                if (false === $permissions->search($permission, true)) {
                    return false;
                }
            }

            return true;
        });

        return $roles;
    }
}
