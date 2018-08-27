<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'source'     => $this->type == 'video' ? $this->source : image($this->source),
            'name'       => $this->name,
            'type'       => $this->type,
            'meta'       => $this->meta,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'tags'       => $this->tagNames()
        ];
    }
}
