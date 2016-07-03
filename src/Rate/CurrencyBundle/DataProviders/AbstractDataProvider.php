<?php

namespace Rate\CurrencyBundle\DataProviders;

use Buzz\Browser;
use Rate\CurrencyBundle;

abstract class AbstractDataProvider implements CurrencyBundle\CurrencyInterface\ExchangeRateProvider
{
    
    /** @var  Browser */
    protected $browser;

    public function __construct(Browser $buzz)
    {
        $this->browser = $this->setBrowser($buzz);
    }

    abstract public function getRateValues($currencies = self::DEFAULT_CURRENCIES);

    /**
     * @param $url
     * @return string
     */
    public function getRequestContent($url)
    {
        $browser = new Browser();
        $response = $browser->get($url);
        return $response->getContent();
    }
    
    /**
     * @return Browser
     */
    public function getBrowser()
    {
        return $this->browser;
    }

    /**
     * @param Browser $browser
     */
    public function setBrowser($browser)
    {
        $this->browser = $browser;
    }
}
