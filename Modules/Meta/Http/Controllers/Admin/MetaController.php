<?php

namespace Modules\Meta\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Modules\Meta\Models\Meta;
use Modules\Core\Utils\RequestSearchQuery;
use Modules\Meta\Contracts\MetaRepository;
use Modules\Meta\Http\Requests\StoreMetaRequest;
use Modules\Meta\Http\Requests\UpdateMetaRequest;
use Modules\Core\Http\Controllers\Admin\AdminController;

class MetaController extends AdminController
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
     * Show the application dashboard.
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function search(Request $request)
    {
        $query = $this->metas->query();

        $requestSearchQuery = new RequestSearchQuery($request, $query, [
            'title',
            'description',
        ]);

        if ($request->get('exportData')) {
            return $requestSearchQuery->export([
                'route',
                'metable_type',
                'title',
                'description',
                'created_at',
                'updated_at',
            ],
                [
                    __('validation.attributes.route'),
                    __('validation.attributes.metable_type'),
                    __('validation.attributes.title'),
                    __('validation.attributes.description'),
                    __('labels.created_at'),
                    __('labels.updated_at'),
                ],
                'metas');
        }

        return $requestSearchQuery->result([
            'metas.id',
            'route',
            'metable_type',
            'metable_id',
            'title',
            'description',
            'created_at',
            'updated_at',
        ]);
    }

    /**
     * @param Meta $meta
     *
     * @return Meta
     */
    public function show(Meta $meta)
    {
        return $meta;
    }

    /**
     * @param StoreMetaRequest $request
     *
     * @return mixed
     */
    public function store(StoreMetaRequest $request)
    {
        $this->authorize('create metas');

        $this->metas->store($request->input());

        return $this->redirectResponse($request, __('alerts.backend.metas.created'));
    }

    /**
     * @param Meta              $meta
     * @param UpdateMetaRequest $request
     *
     * @return mixed
     */
    public function update(Meta $meta, UpdateMetaRequest $request)
    {
        $this->authorize('edit metas');

        $this->metas->update($meta, $request->input());

        return $this->redirectResponse($request, __('alerts.backend.metas.updated'));
    }

    /**
     * @param Meta    $meta
     * @param Request $request
     *
     * @return mixed
     */
    public function destroy(Meta $meta, Request $request)
    {
        $this->authorize('delete metas');

        $this->metas->destroy($meta);

        return $this->redirectResponse($request, __('alerts.backend.metas.deleted'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function batchAction(Request $request)
    {
        $action = $request->get('action');
        $ids = $request->get('ids');

        switch ($action) {
            case 'destroy':
                $this->authorize('delete metas');

                $this->metas->batchDestroy($ids);

                return $this->redirectResponse($request, __('alerts.backend.metas.bulk_destroyed'));
                break;
        }

        return $this->redirectResponse($request, __('alerts.backend.actions.invalid'), 'error');
    }
}
