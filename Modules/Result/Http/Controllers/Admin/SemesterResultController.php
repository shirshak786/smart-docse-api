<?php

namespace Modules\Result\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Admin\AdminController;
use Modules\Core\Utils\RequestSearchQuery;
use Modules\Result\Models\SemesterResult;

class SemesterResultController extends AdminController
{
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
}
