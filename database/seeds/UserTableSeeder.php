<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new AdminWithEverything)->create(['email' => 'admin@missions.me']);

        (new InternWithEverything)->create([
            'email' => 'intern@missions.me'
        ]);

        (new InternWithEverything)->times(10)->create();
        (new UserWithEverything)->times(10)->create();
        (new NewUser)->times(10)->create();
    }
}
