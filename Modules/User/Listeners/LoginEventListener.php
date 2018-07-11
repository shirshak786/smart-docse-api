<?php

namespace Modules\User\Listeners;

use Illuminate\Auth\Events\Login;
use Modules\User\Contracts\AccountRepository;

class LoginEventListener
{
    protected $users;

    public function __construct(AccountRepository $users)
    {
        $this->users = $users;
    }

    public function handle(Login $event)
    {
        $this->users->login($event->user);
    }
}
