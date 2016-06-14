<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Note;
use League\Fractal\TransformerAbstract;

class NoteTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'author'
    ];

    /**
     * Transform the object into a basic array
     *
     * @param Note $note
     * @return array
     */
    public function transform(Note $note)
    {
        $array = [
            'id'         => $note->id,
            'subject'    => $note->subject,
            'content'    => $note->content,
            'created_at' => $note->created_at->toDateTimeString(),
            'updated_at' => $note->updated_at->toDateTimeString(),
            'links'      => [
                [
                    'rel' => 'self',
                    'uri' => '/notes/' . $note->id,
                ]
            ]
        ];

        return $array;
    }

    /**
     * Include Author
     *
     * @param Note $note
     * @return \League\Fractal\Resource\Item
     */
    public function includeAuthor(Note $note)
    {
        $author = $note->user;

        return $this->collection($author, new UserTransformer);
    }
}