<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testLoginForm(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login') // replace with your application's login URL
            ->waitFor('#email', 10) // wait for the element with id 'email' to appear, up to 10 seconds
            ->type('#email', 'test@example.com') // replace with a valid email
            ->type('#password', 'password') // replace with a valid password
            ->press('Login') // assuming the submit button has the text 'Login'
            ->assertPathIs('/home'); // assuming the user is redirected to '/home' after successful login
        });
    }
}
