<?php

namespace Rate\CurrencyBundle\DataProviders;

use Rate\CurrencyBundle;
use Rate\CurrencyBundle\Exceptions\LogicException;

class Yahoo extends AbstractDataProvider
{

    const URL = "https://query.yahooapis.com/v1/public/yql?q=select+*+from+yahoo.finance.xchange+where+pair+=+%22{{CURRENCIES_LIST}}%22&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=";

    const DEFAULT_TIMEZONE = "Europe/Moscow";

    /**
     * {@inheritdoc}
     */
    public function getRateValues($currencies = self::DEFAULT_CURRENCIES)
    {
        $currenciesArray = array_map(function ($value) {
            return trim($value);
        }, explode(",", $currencies));

        $currenciesListModified = array_map(function ($value) {
            return strtoupper($value) . static::CURRENCY_RUBBLE;
        }, $currenciesArray);

        $requestUrl = str_replace('{{CURRENCIES_LIST}}', implode(',', $currenciesListModified), self::URL);
        $responseContent = $this->getRequestContent($requestUrl);

        $responseData = json_decode($responseContent, true);
        if (empty($responseData)) {
            throw new LogicException(sprintf("Response data isn't a valid json. Content: %s", $responseContent));
        }
        $queryResult = $responseData['query'];

        if (empty($queryResult['created'])) {
            throw new LogicException(sprintf("Unexpected response from server. Content: %s", $responseContent));
        }

        $created = new \DateTime($queryResult['created']);
        $created->setTimezone(new \DateTimeZone(static::DEFAULT_TIMEZONE));

        if (empty($queryResult['results']['rate']) || !is_array($queryResult['results']['rate'])) {
            throw new LogicException(sprintf("Can't find rate section in response"));
        }

        $rates = [];
        foreach ($queryResult['results']['rate'] as $rateData) {
            $rateKey = explode("/", $rateData['Name'])[0];
            $rates[$rateKey] = $rateData['Rate'];
        }

        return [
            'date' => $created->format(static::DATE_FORMAT),
            'rates' => $rates
        ];
    }
}
