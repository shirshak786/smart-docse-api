<?php

namespace Modules\Result\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Modules\Result\Models\SemesterResult;
use Modules\Core\Utils\RequestSearchQuery;
use Modules\Core\Http\Controllers\Admin\AdminController;
use Modules\Result\Http\Requests\Admin\StoreSemesterResultRequest;
use Modules\Result\Http\Requests\Admin\UpdateSemesterResultRequest;

class SemesterResultController extends AdminController
{
    public function show(SemesterResult $result)
    {
        $this->authorize('show', $result);

        return $result;
    }

    public function search(Request $request)
    {
        $requestSearchQuery = new RequestSearchQuery($request, SemesterResult::query(), [
            'subject',
            'semester',
            'faculty',
        ]);

        if ($request->get('exportData')) {
            return $requestSearchQuery->export([
                'subject',
                'semester',
                'faculty',
                'created_at',
                'updated_at',
            ],
                [
                    'Subject',
                    'Semester',
                    'Faculty',
                    'Created At',
                    'Updated At',
                ],
                'news');
        }

        return $requestSearchQuery->result();
    }

    public function store(StoreSemesterResultRequest $request)
    {
        $this->authorize('store', SemesterResult::class);

        $result = SemesterResult::create($request->all());

        return $this->redirectResponse($request, 'The results has succesfully been created');
    }

    public function update(UpdateSemesterResultRequest $request, SemesterResult $result)
    {
        $this->authorize('update', SemesterResult::class);
        $result->update($request->all());

        return $this->redirectResponse($request, 'The results has succesfully been updated');
    }

    public function destroy(Request $request, SemesterResult $result)
    {
        $this->authorize('delete', $result);

        $result->delete();

        return $this->redirectResponse($request, 'The results has succesfully been deleted');
    }
}
