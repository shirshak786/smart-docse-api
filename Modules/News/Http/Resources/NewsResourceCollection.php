<?php

namespace Modules\News\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NewsResourceCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
        ];
    }
}
