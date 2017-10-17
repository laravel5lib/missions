<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Questionnaire;
use League\Fractal\TransformerAbstract;

class QuestionnaireTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * Transform the object into a basic array
     *
     * @param Questionnaire $questionnaire
     * @return array
     */
    public function transform(Questionnaire $questionnaire)
    {
        $array = [
            'id' => $questionnaire->id,
            'reservation_id' => $questionnaire->reservation_id,
            'type' => $questionnaire->type,
            'content' => $questionnaire->content,
            'created_at' => $questionnaire->created_at->toDateTimeString(),
            'updated_at' => $questionnaire->updated_at->toDateTimeString(),
            'links'        => [
                [
                    'rel' => 'self',
                    'uri' => '/questionnaires/' . $questionnaire->id,
                ]
            ]
        ];

        return $array;
    }
}
