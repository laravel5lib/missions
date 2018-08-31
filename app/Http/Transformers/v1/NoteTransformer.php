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
        'user'
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
            'source' => [
                'type' => $note->noteable_type,
                'id' => $note->noteable_id
            ],
            'links'      => [
                [
                    'rel' => 'self',
                    'uri' => '/api/notes/' . $note->id,
                ]
            ]
        ];

        return $array;
    }

    /**
     * Include User
     *
     * @param Note $note
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(Note $note)
    {
        $user = $note->user ?? return null;

        return $this->item($user, new UserTransformer);
    }
}
