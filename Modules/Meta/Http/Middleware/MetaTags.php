<?php

namespace Modules\Meta\Http\Middleware;

use Closure;
use Artesaos\SEOTools\Facades\SEOMeta;
use Modules\Meta\Contracts\MetaRepository;

class MetaTags
{
    /**
     * @var MetaRepository
     */
    protected $metas;

    public function __construct(MetaRepository $metas)
    {
        $this->metas = $metas;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $routeName = $request->route()->getName();

        $meta = $this->metas->find($routeName);

        if ($meta) {
            SEOMeta::setTitle($meta->title);
            SEOMeta::setDescription($meta->description);
        }

        return $next($request);
    }
}
