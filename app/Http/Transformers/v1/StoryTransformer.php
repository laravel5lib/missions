<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Story;
use League\Fractal\TransformerAbstract;

class StoryTransformer extends TransformerAbstract
{
    /**
     * Transform the object into a basic array
     *
     * @param Story $story
     * @return array
     */
    public function transform(Story $story)
    {
        $story->load('author');

        $array = [
            'id'         => $story->id,
            'title'      => $story->title,
            'author'     => $story->author->name,
            'created_at' => $story->created_at->toDateTimeString(),
            'updated_at' => $story->updated_at->toDateTimeString(),
            'preview'    => substr($story->content, 0, 200).' ...',
            'content'    => $story->content,
            'links'      => [
                [
                    'rel' => 'self',
                    'uri' => '/stories/' . $story->id,
                ]
            ]
        ];

        if ($story->pivot) {
            $array['published_at'] = $story->pivot->published_at->toDateTimeString();
        }

        return $array;
    }
}
