<?php

namespace Modules\User\Repositories;

use Exception;
use Illuminate\Support\Arr;
use Modules\User\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\User\Events\UserCreated;
use Modules\User\Events\UserDeleted;
use Modules\User\Events\UserUpdated;
use Modules\User\Contracts\UserRepository;
use Illuminate\Contracts\Config\Repository;
use Modules\Core\Exceptions\GeneralException;
use Mcamara\LaravelLocalization\LaravelLocalization;
use Modules\Core\Repositories\EloquentBaseRepository;
use Modules\Role\Repositories\Contracts\RoleRepository;

class EloquentUserRepository extends EloquentBaseRepository implements UserRepository
{
    protected $localization;
    protected $config;

    protected $roles;

    public function __construct(
        User $user,
        RoleRepository $roles,
        LaravelLocalization $localization,
        Repository $config
    ) {
        parent::__construct($user);
        $this->roles = $roles;
        $this->localization = $localization;
        $this->config = $config;
    }

    /**
     * @param string $slug
     *
     * @return User
     */
    public function findBySlug($slug)
    {
        return $this->query()->whereSlug($slug)->first();
    }

    /**
     * @param array $input
     * @param bool  $confirmed
     *
     * @throws GeneralException
     *
     * @return \Modules\User\Models\User
     */
    public function store(array $input, $confirmed = false)
    {
        /** @var User $user */
        $user = $this->make(Arr::only($input, ['name', 'email', 'active']));

        if (isset($input['password'])) {
            $user->password = Hash::make($input['password']);
        }
        $user->confirmed = $confirmed;

        if (empty($user->locale)) {
            $user->locale = $this->localization->getDefaultLocale();
        }

        if (empty($user->timezone)) {
            $user->timezone = $this->config->get('app.timezone');
        }

        if (! $this->save($user, $input)) {
            throw new GeneralException(__('exceptions.backend.users.create'));
        }

        event(new UserCreated($user));

        return $user;
    }

    /**
     * @param User  $user
     * @param array $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \Modules\User\Models\User
     */
    public function update(User $user, array $input)
    {
        if (! $user->can_edit) {
            throw new GeneralException(__('exceptions.backend.users.first_user_cannot_be_edited'));
        }

        $user->fill(Arr::except($input, 'password'));

        if ($user->is_super_admin && ! $user->active) {
            throw new GeneralException(__('exceptions.backend.users.first_user_cannot_be_disabled'));
        }

        if (! $this->save($user, $input)) {
            throw new GeneralException(__('exceptions.backend.users.update'));
        }

        event(new UserUpdated($user));

        return $user;
    }

    private function save(User $user, array $input)
    {
        if (isset($input['password']) && ! empty($input['password'])) {
            $user->password = Hash::make($input['password']);
        }

        if (! $user->save()) {
            return false;
        }

        $roles = $input['roles'] ?? [];

        if (! empty($roles)) {
            $allowedRoles = $this->roles->getAllowedRoles()->keyBy('id');

            foreach ($roles as $id) {
                if (! $allowedRoles->has($id)) {
                    throw new GeneralException(__('exceptions.backend.users.cannot_set_superior_roles'));
                }
            }
        }

        $user->roles()->sync($roles);

        return true;
    }

    public function destroy(User $user)
    {
        if (! $user->can_delete) {
            throw new GeneralException(__('exceptions.backend.users.first_user_cannot_be_destroyed'));
        }

        if (! $user->delete()) {
            throw new GeneralException(__('exceptions.backend.users.delete'));
        }

        event(new UserDeleted($user));

        return true;
    }

    /**
     * @param User $user
     *
     * @throws Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function impersonate(User $user)
    {
        if ($user->is_super_admin) {
            throw new GeneralException(__('exceptions.backend.users.first_user_cannot_be_impersonated'));
        }

        $authenticatedUser = auth()->user();

        if ($authenticatedUser->id === $user->id
            || session()->get('admin_user_id') === $user->id
        ) {
            return redirect()->route('admin.home');
        }

        if (! session()->get('admin_user_id')) {
            session(['admin_user_id' => $authenticatedUser->id]);
            session(['admin_user_name' => $authenticatedUser->name]);
            session(['temp_user_id' => $user->id]);
        }

        //Login user
        auth()->loginUsingId($user->id);

        return redirect(home_route());
    }

    /**
     * @param array $ids
     *
     * @throws \Exception|\Throwable
     *
     * @return mixed
     */
    public function batchDestroy(array $ids)
    {
        DB::transaction(function () use ($ids) {
            // This wont call eloquent events, change to destroy if needed
            if ($this->query()->whereIn('id', $ids)->delete()) {
                return true;
            }

            throw new GeneralException(__('exceptions.backend.users.delete'));
        });

        return true;
    }

    /**
     * @param array $ids
     *
     * @throws \Exception|\Throwable
     *
     * @return mixed
     */
    public function batchEnable(array $ids)
    {
        DB::transaction(function () use ($ids) {
            if ($this->query()->whereIn('id', $ids)
                ->update(['active' => true])
            ) {
                return true;
            }

            throw new GeneralException(__('exceptions.backend.users.update'));
        });

        return true;
    }

    /**
     * @param array $ids
     *
     * @throws \Exception|\Throwable
     *
     * @return mixed
     */
    public function batchDisable(array $ids)
    {
        DB::transaction(function () use ($ids) {
            if ($this->query()->whereIn('id', $ids)
                ->update(['active' => false])
            ) {
                return true;
            }

            throw new GeneralException(__('exceptions.backend.users.update'));
        });

        return true;
    }
}
