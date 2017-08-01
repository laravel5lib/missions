<?php

use Illuminate\Database\Seeder;

class AccountingTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classes = collect([
            'Angel House',
            'Angel House:Angel Houses',
            'Angel House:Churches / Wells',
            'Angel House:Freedom House',
            'Ministry - General',
            'Ministry - General:General',
            'Ministry - General:Road Income & Expense',
            'Missions.Me',
            'Missions.Me:1N1D - 2017',
            'Missions.Me:1N1D - 2017:.General',
            'Missions.Me:1N1D - 2017:Medical',
            'Missions.Me:1N1D - 2017:Stadium Outreach & Humanitarian',
            'Missions.Me:1N1D - 2017:Team',
            'Missions.Me:India - June 2017',
            'MMC',
            'MMC:Campus'
        ]);
        
        $classes->each(function ($class) {
            factory(App\Models\v1\AccountingClass::class)->create([
                'name' => $class
            ]);
        });

        $items = collect([
            'Missionary Donation',
            'General Donation'
        ]);
        
        $items->each(function ($item) {
            factory(App\Models\v1\AccountingItem::class)->create([
                'name' => $item
            ]);
        });
    }
}
