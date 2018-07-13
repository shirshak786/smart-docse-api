<?php

namespace Modules\Redirection\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\Core\Exceptions\GeneralException;
use Modules\Core\Repositories\EloquentBaseRepository;
use Modules\Redirection\Models\Redirection;
use Modules\Redirection\Repositories\Contracts\RedirectionRepository;

class EloquentRedirectionRepository extends EloquentBaseRepository implements RedirectionRepository
{

    public function __construct(Redirection $redirection)
    {
        parent::__construct($redirection);
    }

    public function find($source)
    {
        return $this->query()->whereSource($source)->first();
    }

    public function store(array $input)
    {
        $redirection = $this->make($input);

        if ($this->find($redirection->source)) {
            throw new GeneralException(__('exceptions.backend.redirections.already_exist'));
        }

        if (! $redirection->save()) {
            throw new GeneralException(__('exceptions.backend.redirections.create'));
        }

        return $redirection;
    }


    public function update(Redirection $redirection, array $input)
    {
        if (($existingRedirection = $this->find($redirection->source))
            && $existingRedirection->id !== $redirection->id
        ) {
            throw new GeneralException(__('exceptions.backend.redirections.already_exist'));
        }

        if (! $redirection->update($input)) {
            throw new GeneralException(__('exceptions.backend.redirections.update'));
        }

        return $redirection;
    }


    public function destroy(Redirection $redirection)
    {
        if (! $redirection->delete()) {
            throw new GeneralException(__('exceptions.backend.redirections.delete'));
        }

        return true;
    }


    public function batchDestroy(array $ids)
    {
        DB::transaction(function () use ($ids) {
            if ($this->query()->whereIn('id', $ids)->delete()) {
                return true;
            }

            throw new GeneralException(__('exceptions.backend.redirections.delete'));
        });

        return true;
    }

    public function batchEnable(array $ids)
    {
        DB::transaction(function () use ($ids) {
            if ($this->query()->whereIn('id', $ids)
                ->update(['active' => true])
            ) {
                return true;
            }

            throw new GeneralException(__('exceptions.backend.redirections.update'));
        });

        return true;
    }

    public function batchDisable(array $ids)
    {
        DB::transaction(function () use ($ids) {
            if ($this->query()->whereIn('id', $ids)
                ->update(['active' => false])
            ) {
                return true;
            }

            throw new GeneralException(__('exceptions.backend.redirections.update'));
        });

        return true;
    }
}
