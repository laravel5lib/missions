<?php
namespace App\Http\Transformers\v1\Metrics;
use League\Fractal\TransformerAbstract;

class TeamMetricsTransformer extends TransformerAbstract
{
    public function transform($team)
    {
        return $team;
    }
}