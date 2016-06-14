<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Todo;
use League\Fractal\TransformerAbstract;

class TodoTransformer extends TransformerAbstract
{
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
            'links'        => [
                [
                    'rel' => 'self',
                    'uri' => '/todos/' . $todo->id,
                ]
            ]
        ];

        return $array;
    }
}