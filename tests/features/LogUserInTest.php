<?php

use Illuminate\Support\Facades\Session;

class LogUserInTest extends TestCase
{

    /**
     * @test
     */
    public function see_login_page()
    {
         $this->visit('/')
              ->click('Login')
              ->seePageIs('/login');
    }

    /**
     * @test
     */
    public function log_a_user_in()
    {
        $user = factory(App\Models\v1\User::class)->create(['password' => bcrypt('secret')]);

        Session::start();

        $this->json('POST', '/login', [
                'email' => $user->email,
                'password' => 'secret',
                '_token' => csrf_token()
            ])
             ->seeJsonStructure([
                    'redirect_to', 'token'
                ])
             ->seeJson([
                    'redirect_to' => '/dashboard'
                ]);
    }
}
