<?php

namespace Modules\Meta\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\Core\Exceptions\GeneralException;
use Modules\Core\Repositories\EloquentBaseRepository;
use Modules\Meta\Contracts\MetaRepository;
use Modules\Meta\Models\Meta;

/**
 * Class EloquentMetaRepository.
 */
class EloquentMetaRepository extends EloquentBaseRepository implements MetaRepository
{
    /**
     * EloquentMetaRepository constructor.
     *
     * @param Meta $redirection
     */
    public function __construct(Meta $redirection)
    {
        parent::__construct($redirection);
    }

    /**
     * @param $route
     *
     * @return Meta
     */
    public function find($route)
    {
        return $this->query()->whereRoute($route)->first();
    }

    public function store(array $input)
    {
        $meta = $this->make($input);

        if ($this->find($meta->route)) {
            throw new GeneralException(__('exceptions.backend.metas.already_exist'));
        }

        if (! $meta->save()) {
            throw new GeneralException(__('exceptions.backend.metas.create'));
        }

        return $meta;
    }

    public function update(Meta $meta, array $input)
    {
        if ($meta->route) {
            $existingMeta = $this->find($meta->route);

            if ($existingMeta->id !== $meta->id) {
                throw new GeneralException(__('exceptions.backend.metas.already_exist'));
            }
        }

        if (! $meta->update($input)) {
            throw new GeneralException(__('exceptions.backend.metas.update'));
        }

        return $meta;
    }

    /**
     * @param Meta $meta
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(Meta $meta)
    {
        if (! $meta->delete()) {
            throw new GeneralException(__('exceptions.backend.metas.delete'));
        }

        return true;
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

            throw new GeneralException(__('exceptions.backend.metas.delete'));
        });

        return true;
    }
}
