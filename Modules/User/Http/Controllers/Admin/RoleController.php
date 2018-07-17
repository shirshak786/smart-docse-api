<?php

namespace Modules\User\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Modules\Role\Models\Role;
use Modules\Core\Utils\RequestSearchQuery;
use Modules\User\Http\Requests\StoreRoleRequest;
use Modules\User\Http\Requests\UpdateRoleRequest;
use Modules\Role\Repositories\Contracts\RoleRepository;
use Modules\Core\Http\Controllers\Admin\AdminController;

class RoleController extends AdminController
{
    protected $roles;

    public function __construct(RoleRepository $roles)
    {
        $this->roles = $roles;
    }

    public function search(Request $request)
    {
        $query = $this->roles->query();

        $requestSearchQuery = new RequestSearchQuery($request, $query);

        if ($request->get('exportData')) {
            return $requestSearchQuery->export([
                'name',
                'order',
                'display_name',
                'description',
                'created_at',
                'updated_at',
            ],
                [
                    __('validation.attributes.name'),
                    __('validation.attributes.order'),
                    __('validation.attributes.display_name'),
                    __('validation.attributes.description'),
                    __('labels.created_at'),
                    __('labels.updated_at'),
                ],
                'roles');
        }

        return $requestSearchQuery->result([
            'roles.id',
            'name',
            'order',
            'display_name',
            'description',
            'created_at',
            'updated_at',
        ]);
    }

    public function show(Role $role)
    {
        return $role;
    }

    public function getPermissions()
    {
        return config('permissions');
    }

    public function store(StoreRoleRequest $request)
    {
        $this->authorize('create roles');

        $this->roles->store($request->input());

        return $this->redirectResponse($request, __('alerts.backend.roles.created'));
    }

    public function update(Role $role, UpdateRoleRequest $request)
    {
        $this->authorize('edit roles');

        $this->roles->update($role, $request->input());

        return $this->redirectResponse($request, __('alerts.backend.roles.updated'));
    }

    public function destroy(Role $role, Request $request)
    {
        $this->authorize('delete roles');

        $this->roles->destroy($role);

        return $this->redirectResponse($request, __('alerts.backend.roles.deleted'));
    }
}
