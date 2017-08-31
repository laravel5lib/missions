<?php

use App\Models\v1\Donor;
use FactoryStories\FactoryStory;

class RandomDonor extends FactoryStory
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
        $type = array_rand([
            'existing_member' => 'existing_member',
            'existing_guest' => 'existing_guest',
            'new_guest' => 'new_guest'
        ]);

        switch ($type) {
            case 'existing_member':
                return Donor::whereNotNull('account_id')->get()->random() ?: factory(Donor::class)->create();
                break;

            case 'existing_guest':
                return Donor::whereNull('account_id')->get()->random() ?: factory(Donor::class)->create();
                break;

            case 'new_guest':
                return factory(Donor::class)->create();
                break;

            default:
                return factory(Donor::class)->create();
                break;
        }
    }
}
