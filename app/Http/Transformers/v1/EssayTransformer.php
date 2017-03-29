<?php

namespace App\Http\Transformers\v1;

use App\models\v1\Essay;
use League\Fractal\TransformerAbstract;

class EssayTransformer extends TransformerAbstract
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
     * Turn this item object into a generic array
     *
     * @param Essay $essay
     * @return array
     */
    public function transform(Essay $essay)
    {
        return [
            'id'          => $essay->id,
            'user_id'     => $essay->user_id,
            'author_name' => $essay->author_name,
            'subject'     => $essay->subject,
            'content'     => $essay->content,
            'created_at'  => $essay->created_at->toDateTimeString(),
            'updated_at'  => $essay->updated_at->toDateTimeString()
        ];
    }

    /**
     * Include User
     *
     * @param Essay $essay
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(Essay $essay)
    {
        $user = $essay->user;

        return $this->item($user, new UserTransformer);
    }
}