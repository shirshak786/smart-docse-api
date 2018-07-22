<?php

namespace Modules\News\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Modules\News\Models\News;
use Modules\Core\Utils\RequestSearchQuery;
use Modules\News\Http\Requests\StoreNewsRequest;
use Modules\News\Http\Requests\UpdateNewsRequest;
use Modules\Core\Http\Controllers\Admin\AdminController;

class NewsController extends AdminController
{
    public function show(News $news)
    {
        $this->authorize('show', $news);

        return $news;
    }

    public function search(Request $request)
    {
        $requestSearchQuery = new RequestSearchQuery($request, News::query(), [
            'title',
            'content',
        ]);

        if ($request->get('exportData')) {
            return $requestSearchQuery->export([
                'title',
                'content',
                'published_at',
                'created_at',
                'updated_at',
            ],
                [
                    'Title',
                    'Content',
                    'Published Date',
                    'Created At',
                    'Updated At',
                ],
                'news');
        }

        return $requestSearchQuery->result();
    }

    public function store(StoreNewsRequest $request)
    {
        $this->authorize('store', News::class);

        $news = News::make($request->all());
        $news->author_id = $request->user()->id;
        if ($request->hasFile('cover_image')) {
            $news->addMedia($request->cover_image)->toMediaCollection('cover_image');
        }

        $news->saveOrFail();

        return $this->redirectResponse($request, 'The news has succesfully been created');
    }

    public function update(UpdateNewsRequest $request, News $news)
    {
        $this->authorize('update', $news);

        $news->update($request->all());
        $news->author_id = $request->user()->id;

        if ($request->hasFile('cover_image_upload') && $news->getFirstMedia('cover_image')) {
            $news->getFirstMedia('cover_image')->delete();
        }

        if ($request->hasFile('cover_image_upload')) {
            $news->addMedia($request->file('cover_image_upload'))->toMediaCollection('cover_image');
        }
        $news->saveOrFail();

        return $this->redirectResponse($request, 'The news has succesfully been updated');
    }

    public function destroy(News $news, Request $request)
    {
        $this->authorize('delete', $news);

        $news->delete();

        return $this->redirectResponse($request, 'The news has succesfully been deleted');
    }
}
