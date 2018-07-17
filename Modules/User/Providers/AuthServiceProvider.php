<?php

namespace Modules\User\Providers;

use Modules\Blog\Models\Post;
use Modules\User\Models\User;
use Illuminate\Support\Facades\Gate;
use Modules\Blog\Policies\PostPolicy;
use Modules\User\Policies\UserPolicy;
use Modules\User\Contracts\AccountRepository;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
            User::class => UserPolicy::class,
            Post::class => PostPolicy::class,
        ];

    /**
     * Register any authentication / authorization services.
     *
     * @throws \InvalidArgumentException
     */
    public function boot()
    {
        $this->registerPolicies();

        $accountRepository = $this->app->make(AccountRepository::class);

        foreach (config('permissions') as $key => $permissions) {
            Gate::define($key, function (User $user) use ($accountRepository, $key) {
                return $accountRepository->hasPermission($user, $key);
            });
        }
    }
}
