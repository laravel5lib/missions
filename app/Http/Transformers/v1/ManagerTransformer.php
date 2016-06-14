<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Manager;
use League\Fractal\TransformerAbstract;

class ManagerTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'group', 'user'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param Manager $manager
     * @return array
     */
    public function transform(Manager $manager)
    {
        $manager->load('user');

        return [
            'id'         => $manager->id,
            'name'       => $manager->user->name,
            'email'      => $manager->user->email,
            'gender'     => $manager->user->gender,
            'created_at' => $manager->created_at->toDateTimeString(),
            'updated_at' => $manager->updated_at->toDateTimeString(),
            'links'      => [
                [
                    'rel' => 'self',
                    'uri' => '/managers/' . $manager->id,
                ]
            ],
        ];
    }

    /**
     * Include User
     *
     * @param Manager $manager
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(Manager $manager)
    {
        $user = $manager->user;

        return $this->item($user, new UserTransformer);
    }

    /**
     * Include Group
     *
     * @param Manager $manager
     * @return \League\Fractal\Resource\Item
     */
    public function includeGroup(Manager $manager)
    {
        $group = $manager->group;

        return $this->item($group, new GroupTransformer);
    }
}