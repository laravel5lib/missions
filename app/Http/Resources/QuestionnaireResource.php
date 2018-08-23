<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionnaireResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array = [
            'id' => $this->id,
            'reservation_id' => $this->reservation_id,
            'content' => $this->content,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String()
        ];

        if ($this->type == 'airport_preference') {
            $array['content'] = collect($this->content)->map(function ($iata) {
                return airport($iata)->name.' ('.$iata.')';
            })->all();
        }

        return $array;
    }
}
