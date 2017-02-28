<?php

use Illuminate\Support\Facades\Session;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogUserInTest extends TestCase
{   
    use DatabaseMigrations, DatabaseTransactions;

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
