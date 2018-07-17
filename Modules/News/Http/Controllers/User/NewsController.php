<?php

namespace Modules\News\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Modules\News\Http\Resources\NewsResourceCollection;
use Modules\News\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        return new NewsResourceCollection(News::get());
    }
}
