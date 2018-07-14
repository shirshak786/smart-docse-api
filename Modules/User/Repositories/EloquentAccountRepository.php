<?php

namespace Modules\User\Repositories;

use Exception;
use Carbon\Carbon;
use Modules\Core\Exceptions\GeneralException;
use Modules\Core\Repositories\EloquentBaseRepository;
use Modules\User\Contracts\AccountRepository;
use Modules\User\Contracts\UserRepository;
use Modules\User\Models\SocialLogin;
use Modules\User\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laravel\Socialite\AbstractUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\Authenticatable;
use Modules\User\Notifications\SendConfirmation;

/**
 * Class EloquentAccountRepository.
 */
class EloquentAccountRepository extends EloquentBaseRepository implements AccountRepository
{
    protected $users;


    public function __construct(User $user, UserRepository $users)
    {
        parent::__construct($user);
        $this->users = $users;
    }

    public function register(array $input)
    {
        $user = $this->users->store(Arr::only($input, ['name', 'email', 'password']));

        $this->sendConfirmationToUser($user);

        return $user;
    }


    public function login(Authenticatable $user)
    {
        $user->last_access_at = Carbon::now();

        if (! $user->save()) {
            throw new GeneralException(__('exceptions.backend.users.update'));
        }
        session(['permissions' => $user->getPermissions()]);

        return $user;
    }


    public function findOrCreateSocial($provider, AbstractUser $data)
    {
        // Email can be not provided, so set default provider email.
        $user_email = $data->getEmail() ?: "{$data->getId()}@{$provider}.com";

        // Get user with this email or create new one.
        /** @var User $user */
        $user = $this->users->query()->whereEmail($user_email)->first();

        if (! $user) {
            // Registration is not enabled
            if (! config('account.can_register')) {
                throw new GeneralException(__('exceptions.frontend.auth.registration_disabled'));
            }

            $user = $this->users->store([
                'name'   => $data->getName(),
                'email'  => $user_email,
                'active' => true,
            ], true);
        }

        // Save new provider if needed
        if (! $user->getProvider($provider)) {
            $user->providers()->save(new SocialLogin([
                'provider'    => $provider,
                'provider_id' => $data->getId(),
            ]));
        }

        return $user;
    }

    public function hasPermission(Authenticatable $user, $name)
    {
        if ($user->is_super_admin) {
            return true;
        }

        $permissions = session()->get('permissions');

        if ($permissions->isEmpty()) {
            return false;
        }

        return $permissions->contains($name);
    }

    public function update(array $input)
    {
        if (! config('account.updating_enabled')) {
            throw new GeneralException(__('exceptions.frontend.user.updating_disabled'));
        }

        /** @var User $user */
        $user = auth()->user();

        $user->fill(Arr::only($input, ['name', 'email', 'locale', 'timezone']));

        if ($user->isDirty('email')) {
            $user->confirmed = false;
            $this->sendConfirmationToUser($user);
        }

        return $user->save();
    }


    public function changePassword($oldPassword, $newPassword)
    {
        if (! config('account.updating_enabled')) {
            throw new GeneralException(__('exceptions.frontend.user.updating_disabled'));
        }

        /** @var User $user */
        $user = auth()->user();

        if (empty($user->password) || Hash::check($oldPassword, $user->password)) {
            $user->password = Hash::make($newPassword);

            return $user->save();
        }

        throw new GeneralException(__('exceptions.frontend.user.password_mismatch'));
    }

    /**
     * Send mail confirmation.
     */
    public function sendConfirmation()
    {
        /** @var User $user */
        $user = auth()->user();

        $this->sendConfirmationToUser($user);
    }

    /**
     * @param \Modules\User\Models\User $user
     */
    private function sendConfirmationToUser(User $user)
    {
        $user->confirmation_token = Str::random(60);
        $user->save();

        $user->notify(new SendConfirmation($user->confirmation_token));
    }

    /**
     * Send mail confirmation.
     *
     * @param $token
     *
     * @return string|void
     */
    public function confirmEmail($token)
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->confirmation_token === $token) {
            $user->confirmed = true;
            $user->save();
        }
    }

    public function delete()
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->is_super_admin) {
            throw new GeneralException(__('exceptions.backend.users.first_user_cannot_be_destroyed'));
        }

        if (! $user->delete()) {
            throw new GeneralException(__('exceptions.frontend.user.delete_account'));
        }

        return true;
    }
}
