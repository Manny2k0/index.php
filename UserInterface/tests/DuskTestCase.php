<?php

namespace Tests;

use Laravel\Dusk\TestCase as BaseTestCase;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        static::startChromeDriver();
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $options = ['--disable-gpu', '--headless', '--no-sandbox'];

        return $this->createChromeDriver($options);
    }

    /**
     * Create a new instance of the ChromeDriver.
     *
     * @param array $options
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function createChromeDriver($options)
    {
        $chromeOptions = new ChromeOptions();
        foreach ($options as $option) {
            $chromeOptions->addArguments([$option]);
        }

        return RemoteWebDriver::create(
            'http://localhost:9515', // this is the default
            $chromeOptions->toCapabilities()
        );
    }
}
