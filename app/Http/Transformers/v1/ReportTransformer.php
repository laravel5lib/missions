<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Report;
use League\Fractal\TransformerAbstract;

class ReportTransformer extends TransformerAbstract
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
     * @param Report $report
     * @return array
     */
    public function transform(Report $report)
    {
        return [
            'id'           => $report->id,
            'name'         => $report->name,
            'type'         => $report->type,
            'source'       => download_file($report->source),
            'created_at'   => $report->created_at->toDateTimeString(),
            'updated_at'   => $report->updated_at->toDateTimeString(),
            'links'        => [
                [
                    'rel' => 'self',
                    'uri' => '/api/reports/' . $report->id,
                ]
            ]
        ];
    }

    /**
     * Include User
     *
     * @param Report $report
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(Report $report)
    {
        $user = $report->user;

        return $this->item($user, new UserTransformer);
    }
}