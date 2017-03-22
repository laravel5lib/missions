<?php

use Illuminate\Database\Seeder;

class CampaignTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $oneNation = factory(App\Models\v1\Campaign::class, '1n1d2017')->create([
                'avatar_upload_id' => function() {
                    return factory(App\Models\v1\Upload::class, 'avatar')->create([
                        'name' => '1n1d17_white',
                        'source' => 'images/avatars/1n1d17-white-400x400.jpg'
                    ])->id;
                }
            ]);

        $oneNation->slug()->save(factory(App\Models\v1\Slug::class)->make([
                'url' => '1n1d17'
            ]));
        
        $india = factory(App\Models\v1\Campaign::class, 'india')->create([
                'avatar_upload_id' => function() {
                    return factory(App\Models\v1\Upload::class, 'avatar')->create([
                        'name' => 'angels_to_orphans',
                        'source' => 'images/avatars/orphansToAngelsSummer.jpg'
                    ])->id;
                }
            ]);

        $india->slug()->save(factory(App\Models\v1\Slug::class)->make([
                'url' => 'orphans2angels'
            ]));
    }
}
