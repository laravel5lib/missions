<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Todo;
use League\Fractal\TransformerAbstract;

class TodoTransformer extends TransformerAbstract
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
     * @param Todo $todo
     * @return array
     */
    public function transform(Todo $todo)
    {
        $array = [
            'id'           => $todo->id,
            'task'         => $todo->task,
            'completed_at' => $todo->completed_at ? $todo->completed_at->toDateTimeString() : null,
            'source'       => [
                'type' => $todo->todoable_type,
                'id'   => $todo->todoable_id
            ],
            'links'        => [
                [
                    'rel' => 'self',
                    'uri' => '/api/todos/' . $todo->id,
                ]
            ]
        ];

        return $array;
    }

    /**
     * Include User
     *
     * @param Todo $todo
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(Todo $todo)
    {
        $user = $todo->user;

        if (! $user) return null;

        return $this->item($user, new UserTransformer);
    }
}