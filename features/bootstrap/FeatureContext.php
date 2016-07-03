<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
{
    
    /**
     * @Then I wait :arg1 mseconds for the element :arg2
     */
    public function iWaitMsecondsForTheElement($mseconds, $elementId)
    {
        $this->getSession()->wait($mseconds, "$('#" . $elementId . "').length");
    }

    /**
     * @Then I see :arg1 currency isn't null
     */
    public function iSeeCurrencyIsnTNull($currencyName)
    {
        $currencyRate = $this->getSession()->getPage()->find('xpath', '//*[@id="div_'.$currencyName.'"]/*[@class="currency-rate"]');
        if ((float)$currencyRate->getText() <= 0) {
            throw new Exception(sprintf("Rate is null: %s", $currencyRate->getText()));
        }
    }
}
