<?php

namespace Modules\Blog\Repositories;

use Mcamara\LaravelLocalization\LaravelLocalization;
use Modules\Blog\Contracts\TagRepository;
use Modules\Blog\Models\Tag;
use Modules\Core\Repositories\EloquentBaseRepository;

class EloquentTagRepository extends EloquentBaseRepository implements TagRepository
{
    /**
     * @var \Mcamara\LaravelLocalization\LaravelLocalization
     */
    protected $localization;

    /**
     * EloquentUserRepository constructor.
     *
     * @param Tag                                              $tag
     * @param \Mcamara\LaravelLocalization\LaravelLocalization $localization
     */
    public function __construct(Tag $tag, LaravelLocalization $localization)
    {
        parent::__construct($tag);
        $this->localization = $localization;
    }

    /**
     * @param string $slug
     *
     * @return mixed
     */
    public function findBySlug($slug)
    {
        $locale = app()->getLocale();

        return $this->query()->where("slug->{$locale}", $slug)->first();
    }
}
