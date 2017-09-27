<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LogUserInTest extends BrowserKitTestCase
{

    /**
     * @test
     */
    public function see_login_page()
    {
         $this->visit('/')
              ->click('Log In')
              ->seePageIs('/login');
    }

    /**
     * @test
     */
    public function logs_valid_user_in()
    {
        $user = factory(App\Models\v1\User::class)->create([
            'password' => bcrypt('secret')
        ]);

        Session::start();

        $response = $this->call('POST', '/login', [
            'email' => $user->email,
            'password' => 'secret',
            '_token' => csrf_token()
        ]);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertTrue(Auth::check());
    }

    /** @test **/
    public function does_not_log_invalid_user_in()
    {
        Session::start();

        $response = $this->call('POST', '/login', [
            'email' => 'bad@email.com',
            'password' => 'badpasword',
            '_token' => csrf_token()
        ]);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertFalse(Auth::check());
    }
}
