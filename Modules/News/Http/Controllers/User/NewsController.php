<?php

namespace Modules\News\Http\Controllers\User;

use Modules\News\Models\News;
use App\Http\Controllers\Controller;
use Modules\News\Http\Resources\NewsResourceCollection;

class NewsController extends Controller
{
    public function index()
    {
        return new NewsResourceCollection(News::get());
    }
}
