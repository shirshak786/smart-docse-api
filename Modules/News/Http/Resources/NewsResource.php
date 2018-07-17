<?php

namespace Modules\News\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'type' => $this->type_value,
            'status' => $this->status_value,
            'author' => $this->author->name,
            'published_date' => $this->published_date,
            'cover_image_url' => $this->cover_image_url,
        ];
    }
}
