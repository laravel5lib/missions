<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\ProjectPackage;
use League\Fractal;

class ProjectPackageTransformer extends Fractal\TransformerAbstract
{

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'deadlines',
        'costs',
        'type',
        'initiative'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param ProjectPackage $package
     * @return array
     */
    public function transform(ProjectPackage $package)
    {
        return [
            'id'                    => $package->id,
            'project_type_id'       => $package->project_type_id,
            'project_initiative_id' => $package->project_initiative_id,
            'generate_dates'        => (boolean) $package->generate_dates,
            'links'                 => [
                [
                    'rel' => 'self',
                    'uri' => url('/project/packages/' . $package->id),
                ]
            ],
        ];
    }

    /**
     * Include Type
     *
     * @param ProjectPackage $package
     * @return Fractal\Resource\Item
     */
    public function includeType(ProjectPackage $package)
    {
        $type = $package->type;

        return $this->item($type, new ProjectTypeTransformer);
    }

    /**
     * Include Initiative.
     *
     * @param ProjectPackage $package
     * @return Fractal\Resource\Item
     */
    public function includeInitiative(ProjectPackage $package)
    {
        $initiative = $package->initiative;

        return $this->item($initiative, new ProjectInitiativeTransformer);
    }

    /**
     * Include Deadlines
     *
     * @param ProjectPackage $package
     * @return Fractal\Resource\Collection
     */
    public function includeDeadlines(ProjectPackage $package)
    {
        $dates = $package->deadlines;

        return $this->collection($dates, new DeadlineTransformer);
    }

    /**
     * Include Costs
     *
     * @param ProjectPackage $package
     * @return Fractal\Resource\Collection
     */
    public function includeCosts(ProjectPackage $package)
    {
        $enhancements = $package->costs;

        return $this->collection($enhancements, new CostTransformer);
    }
}