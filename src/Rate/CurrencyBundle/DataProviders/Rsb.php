<?php

namespace Rate\CurrencyBundle\DataProviders;

use Rate\CurrencyBundle;
use Rate\CurrencyBundle\Exceptions\LogicException;

class Rsb extends AbstractDataProvider
{

    const URL = "http://www.cbr.ru/scripts/XML_daily.asp";
    
    /**
     * {@inheritdoc}
     */
    public function getRateValues($currencies = self::DEFAULT_CURRENCIES)
    {
        $currenciesArray = array_map(function ($value) {
            return trim($value);
        }, explode(",", $currencies));

        $currenciesListModified = array_map(function ($value) {
            return strtoupper($value);
        }, $currenciesArray);

        $responseContent = $this->getRequestContent(self::URL);

        $responseXml = @simplexml_load_string($responseContent);
        if (empty($responseXml)) {
            throw new LogicException(sprintf("Response data isn't a valid xml. Content: %s", $responseContent));
        }

        $attributes = $responseXml->attributes();
        $createdDate = new \DateTime((string)$attributes->Date);
        
        $rates = [];
        foreach ($responseXml->Valute as $currencyXml) {
            $charCode = (string)$currencyXml->CharCode;
            $currencyValue = (float)str_replace(",", ".", (string)$currencyXml->Value);
            if (in_array($charCode, $currenciesListModified)) {
                $rates[$charCode] = $currencyValue;
            }
        }
        
        return [
            'date' => $createdDate->format(static::DATE_FORMAT),
            'rates' => $rates
        ];
    }
}
