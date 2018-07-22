<?php

namespace Modules\Result\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Modules\Result\Models\SemesterResult;
use Modules\Result\Http\Resources\SemesterResultResourceCollection;

class SemesterResultController extends Controller
{
    public function index()
    {
        return new SemesterResultResourceCollection(SemesterResult::get());
    }
}
