<?php

use Illuminate\Database\Seeder;

class UploadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\v1\Upload::class, 'avatar', config('seeders.uploads'))->create();
        factory(App\Models\v1\Upload::class, 'banner', config('seeders.uploads'))->create();
    }
}
