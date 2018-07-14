<?php

namespace Modules\Redirection\Http\Middleware;

use Closure;
use Modules\Redirection\Repositories\Contracts\RedirectionRepository;

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
