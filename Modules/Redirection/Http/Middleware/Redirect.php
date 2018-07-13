<?php

namespace App\Http\Middleware;

use App\Repositories\Contracts\RedirectionRepository;
use Closure;

class Redirect
{
    protected $redirections;

    public function __construct(RedirectionRepository $redirections)
    {
        $this->redirections = $redirections;
    }

    public function handle($request, Closure $next)
    {
        $redirection = $this->redirections->find($request->getRequestUri());

        if ($redirection && $redirection->active) {
            return redirect($redirection->target, $redirection->type);
        }

        return $next($request);
    }
}
