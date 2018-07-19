<?php

namespace Modules\Result\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Modules\Result\Http\Resources\SemesterResultResourceCollection;
use Modules\Result\Models\SemesterResult;

class SemesterResultController extends Controller
{
    public function index()
    {
        return new SemesterResultResourceCollection(SemesterResult::get());
    }
}
