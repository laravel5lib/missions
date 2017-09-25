<?php

use Illuminate\Support\Facades\Session;

class CreateUserAccountTest extends BrowserKitTestCase
{
    /**
     * @test
     */
    public function see_signup_page()
    {
         $this->visit('/')
              ->click('Sign Up')
              ->seePageIs('/register');
    }

    /**
     * @test
     */
    public function registers_user()
    {
        $newUser = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@email.com',
            'gender' => 'male',
            'birthday' => '1987-07-28',
            'country' => 'us',
            'timezone' => 'America/Los_Angeles',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ];

        $this->post('/register', $newUser);

        $this->assertResponseStatus(302)
             ->seeInDatabase('users', ['email' => 'john@email.com']);
    }
}
