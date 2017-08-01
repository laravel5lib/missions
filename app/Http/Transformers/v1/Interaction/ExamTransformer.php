<?php

namespace App\Http\Transformers\v1\Interaction;

use App\Http\Transformers\v1\RegionTransformer;
use App\Models\v1\Interaction\Exam;
use League\Fractal\TransformerAbstract;

class ExamTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'region'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param Exam $exam
     * @return array
     */
    public function transform(Exam $exam)
    {
        $exam->load('author');
        
        $array = [
            'id'          => $exam->id,
            'author_id'   => $exam->author_id,
            'author_type' => $exam->author_type,
            'author_name' => $exam->author->name,
            'region_id'   => $exam->region_id,
            'name'        => $exam->name,
            'gender'      => $exam->gender,
            'age_group'   => $exam->age_group,
            'decision'    => (bool) $exam->decision,
            'party_size'  => (int) $exam->party_size,
            'email'       => $exam->email,
            'phone'       => $exam->phone,
            'lat'         => $exam->lat,
            'long'        => $exam->long,
            'treatments'  => (array) $exam->treatments,
            'created_at'  => $exam->created_at->toDateTimeString(),
            'updated_at'  => $exam->updated_at->toDateTimeString(),
            'links'       => [
                [
                    'rel' => 'self',
                    'uri' => '/interactions/exams/' . $exam->id,
                ]
            ]
        ];

        return $array;
    }

    /**
     * Include Region
     *
     * @param Exam $exam
     * @return \League\Fractal\Resource\Item
     */
    public function includeRegion(Exam $exam)
    {
        $region = $exam->region;

        return $this->item($region, new RegionTransformer);
    }
}
