<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ContactTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testContactForm(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('http://localhost:63342/index.php/public/Contact.php?_ijt=du78vh3v243uqtjhenvtkus1vs&_ij_reload=RELOAD_ON_SAVE') // replace with your application's URL
            ->waitFor('#address', 10) // wait for the element with id 'address' to appear, up to 10 seconds
            ->type('#address', '123 Test Street') // use '#address' instead of 'address'
            ->type('#zip_code', 'A12 B345') // assuming this is a valid zip code
            ->select('#state', 'antrim') // assuming 'antrim' is a valid option
            ->select('#country', 'United States') // assuming 'United States' is a valid option
            ->press('Pay') // assuming the submit button has the text 'Pay'
            ->assertPathIs('/transaction.php'); // assuming the user is redirected to '/transaction.php' after successful form submission
        });
    }
}
