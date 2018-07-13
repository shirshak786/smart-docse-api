<?php

namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Mcamara\LaravelLocalization\LaravelLocalization;
use Modules\Blog\Contracts\PostRepository;
use Modules\Blog\Contracts\TagRepository;

class AjaxController extends Controller
{

    protected $posts;
    protected $tags;

    /**
     * @var LaravelLocalization
     */
    protected $localization;


    public function __construct(PostRepository $posts, TagRepository $tags, LaravelLocalization $localization)
    {
        $this->posts = $posts;
        $this->tags = $tags;
        $this->localization = $localization;
    }

    /**
     * Global index search.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        return empty($query) ? [] : $this->posts->search($query)->take(50)->get();
    }

    /**
     * Search internal transatables routes.
     *
     * @param Request $request
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    public function routesSearch(Request $request)
    {
        $query = $request->get('q');

        $items = [];

        $routes = __('routes');

        foreach ($routes as $name => $uri) {
            /* @var Route $route */
            if (str_contains($name, $query)
                || str_contains($uri, $query)
            ) {
                $items[] = [
                  'value' => $name,
                  'label' => $uri,
                ];
            }
        }

        return [
            'items' => $items,
        ];
    }

    /**
     * Search tags.
     *
     * @param Request $request
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    public function tagsSearch(Request $request)
    {
        $query = $request->get('q');

        $locale = app()->getLocale();

        return [
            'items' => $this->tags->query()
                ->where("name->{$locale}", 'like', "%$query%")
                ->pluck('name'),
        ];
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function imageUpload(Request $request)
    {
        $uploadedImage = $request->file('upload');

        // Resize image below 600px width if needed
        $image = Image::make($uploadedImage->openFile())->widen(600, function ($constraint) {
            $constraint->upsize();
        });

        $imagePath = "/tmp/{$uploadedImage->getClientOriginalName()}";
        Storage::disk('public')->put($imagePath, $image->stream());

        return [
            'uploaded' => true,
            'url'      => "/storage{$imagePath}",
            'width'    => $image->width(),
            'height'   => $image->height(),
        ];
    }
}
