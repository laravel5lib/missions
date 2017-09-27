<?php

use FactoryStories\FactoryStory;

class RandomTrip extends FactoryStory
{
    /**
     * Here you can create your complex model factory
     * logic
     *
     * @param array $params Array of params
     *
     * @return Mixed
     */
    public function build($params = [])
    {
        $type = [
            'ministry'      => 'MinistryTrip',
            'medical'       => 'MedicalTrip',
            'media'         => 'MediaTrip',
            'family'        => 'FamilyTrip',
            'international' => 'InternationalTrip',
            'leader'        => 'LeaderTrip'
        ];

        return (new $type[array_rand($type)])->create($params);
    }
}
