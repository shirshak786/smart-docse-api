<?php

namespace App\Providers;

use App\Models\Post;
use Modules\User\Models\User;
use App\Policies\PostPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use App\Repositories\Contracts\AccountRepository;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies
        = [

        ];

    /**
     * Register any authentication / authorization services.
     *
     * @throws \InvalidArgumentException
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
