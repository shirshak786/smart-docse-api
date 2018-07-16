<?php

namespace Modules\News\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\News\Http\Requests\StoreNewsRequest;
use Modules\News\Http\Requests\UpdateNewsRequest;
use Modules\News\Http\Resources\NewsResource;
use Modules\News\Models\News;
use function response;

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

    public function store(StoreNewsRequest $request)
    {
        $this->authorize('store',News::class);

        $news= News::create($request->all());
        $news->author_id = $request->user()->id();
        if($request->hasFile('cover_image')){
            $news->addMedia($request->cover_image)->toMediaCollection('cover_image');
        }

        return (new NewsResource($news))->response()->setStatusCode(201);
    }

    public function update(UpdateNewsRequest $request,News $news)
    {
        $this->authorize('update',$news);

        $news->update($request->all());
        $news->author_id = $request->author_id;

        if (! $request->input('cover_image') && $news->getFirstMedia('cover_image')) {
            $news->getFirstMedia('cover_image')->delete();
        }

        if ($request->hasFile('cover_image')) {
            $news->addMedia($request->file('cover_image'))->toMediaCollection('cover_image');
        }

        $news->saveOrFail();

        return (new NewsResource($news))->response()->setStatusCode(201);
    }


    public function destroy(News $news)
    {
        $this->authorize('delete',$news);

        $news->delete();

        return response(null, 204);
    }
}
