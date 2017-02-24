<?php

use Illuminate\Support\Facades\Session;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateUserAccountTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;
    
    /**
     * @test
     */
    public function see_signup_page()
    {
         $this->visit('/')
              ->click('Sign Up')
              ->seePageIs('/login?action=signup');
    }

    /**
     * @test
     */
    public function create_a_user()
    {
        Session::start();
        $user = [
            'name' => 'John Doe',
            'email' => 'john@gmail.com',
            '_token' => csrf_token(),
            'password' => 'secretfoo',
            'password_confirmation' => 'secretfoo',
            'birthday' => '1987-07-28 00:05:00',
            'gender' => 'Male',
            'country_code' => 'us',
            'timezone' => 'America/Detroit'
        ];

        $this->json('POST', '/register', $user)
             ->seeJsonStructure([
                    'redirect_to', 'token'
                ])
             ->seeJson([
                    'redirect_to' => '/dashboard'
                ]);
    }
}
