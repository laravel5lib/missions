<?php

namespace App\Http\Transformers\v1;

use App\models\v1\Essay;
use League\Fractal\TransformerAbstract;

class InfluencerTransformer extends TransformerAbstract
{

    /**
     * Turn this item object into a generic array
     *
     * @param Essay $essay
     * @return array
     */
    public function transform()
    {
        $questionnaire = [

        ];
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
}
