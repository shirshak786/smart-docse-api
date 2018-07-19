<?php

namespace Modules\Result\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SemesterResultResourceCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
        ];
    }
}
