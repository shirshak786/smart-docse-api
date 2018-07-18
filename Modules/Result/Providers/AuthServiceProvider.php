<?php

namespace Modules\News\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Result\Models\SemesterResult;
use Modules\Result\Policies\SemesterResultPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
            SemesterResult::class => SemesterResultPolicy::class,
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
