<?php

namespace Modules\User\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Admin\AdminController;
use Modules\Core\Utils\RequestSearchQuery;
use Modules\Role\Repositories\EloquentRoleRepository;
use Modules\User\Http\Requests\StoreUserRequest;
use Modules\User\Http\Requests\UpdateUserRequest;
use Modules\User\Models\User;
use Modules\User\Repositories\EloquentUserRepository;

class UserController extends AdminController
{
    protected $users;
    protected $roles;

    public function __construct(EloquentUserRepository $users, EloquentRoleRepository $roles)
    {
        $this->users = $users;
        $this->roles = $roles;
    }

    public function getActiveUserCounter()
    {
        return $this->users->query()->whereActive(true)->count();
    }


    public function search(Request $request)
    {
        $requestSearchQuery = new RequestSearchQuery($request, $this->users->query(), [
            'name',
            'email',
        ]);

        if ($request->get('exportData')) {
            return $requestSearchQuery->export([
                'name',
                'email',
                'active',
                'confirmed',
                'last_access_at',
                'created_at',
                'updated_at',
            ],
                [
                    __('validation.attributes.name'),
                    __('validation.attributes.email'),
                    __('validation.attributes.active'),
                    __('validation.attributes.confirmed'),
                    __('labels.last_access_at'),
                    __('labels.created_at'),
                    __('labels.updated_at'),
                ],
                'users');
        }

        return $requestSearchQuery->result();
    }

    /**
     * @param User $user
     *
     * @return User
     */
    public function show(User $user)
    {
        if (! $user->can_edit) {
            // Only Super admin can access himself
            abort(403);
        }

        return $user;
    }

    public function getRoles()
    {
        return $this->roles->getAllowedRoles();
    }

    public function store(StoreUserRequest $request)
    {
        $this->authorize('create users');

        $this->users->store($request->input());

        return $this->redirectResponse($request, __('alerts.backend.users.created'));
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        $this->authorize('edit users');

        $this->users->update($user, $request->input());

        return $this->redirectResponse($request, __('alerts.backend.users.updated'));
    }

    public function destroy(User $user, Request $request)
    {
        $this->authorize('delete users');

        $this->users->destroy($user);

        return $this->redirectResponse($request, __('alerts.backend.users.deleted'));
    }

    public function impersonate(User $user)
    {
        $this->authorize('impersonate users');

        return $this->users->impersonate($user);
    }

    public function batchAction(Request $request)
    {
        $action = $request->get('action');
        $ids = $request->get('ids');

        switch ($action) {
            case 'destroy':
                $this->authorize('delete users');

                $this->users->batchDestroy($ids);

                return $this->redirectResponse($request, __('alerts.backend.users.bulk_destroyed'));
                break;
            case 'enable':
                $this->authorize('edit users');

                $this->users->batchEnable($ids);

                return $this->redirectResponse($request, __('alerts.backend.users.bulk_enabled'));
                break;
            case 'disable':
                $this->authorize('edit users');

                $this->users->batchDisable($ids);

                return $this->redirectResponse($request, __('alerts.backend.users.bulk_disabled'));
                break;
        }

        return $this->redirectResponse($request, __('alerts.backend.actions.invalid'), 'error');
    }

    public function activeToggle(User $user)
    {
        $this->authorize('edit users');
        $user->update(['active' => ! $user->active]);
    }

    public function confirmToggle(User $user)
    {
        $this->authorize('edit users');

        $user->confirmed = ! $user->confirmed;
        $user->save();
    }
}
