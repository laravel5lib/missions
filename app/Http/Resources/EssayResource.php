<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EssayResource extends JsonResource
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
            'id'          => $this->id,
            'user_id'     => $this->user_id,
            'author_name' => $this->author_name,
            'subject'     => $this->subject,
            'content'     => $this->content,
            'created_at'  => $this->created_at->toIso8601String(),
            'updated_at'  => $this->updated_at->toIso8601String(),
            'user'        => new UserResource($this->whenLoaded('user'))
        ];
    }
}
