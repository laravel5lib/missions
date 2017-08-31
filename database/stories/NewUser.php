<?php

use App\Models\v1\Slug;
use App\Models\v1\User;
use FactoryStories\FactoryStory;

class NewUser extends FactoryStory
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
        $user = factory(User::class, 'new')->create();

        $user->assign('member');

        $user->slug()
            ->save(factory(Slug::class)->make([
                'url' => str_slug($user->name)
            ]));
    }
}
