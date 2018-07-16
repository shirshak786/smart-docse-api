<?php

namespace Modules\News\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\News\Models\News;

class AdminController extends Controller
{
    public function index()
    {
        return new NewsResource(News::with('author')->get());
    }

    public function show(News $news){
        $this->authorize('view news');
        return new NewsResource($news);
    }

    public function store()
    {
        $this->authorize('store news');

        $news= News::create()
    }

    public function update()
    {

    }


    public function destroy()
    {

    }
}
