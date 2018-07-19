<?php

namespace Modules\Result\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SemesterResultResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'subject'           => $this->subject,
            'semester'            => $this->semester,
            'faculty'         => $this->faculty,
            'result_data'          => $this->result_data,
        ];
    }
}
