<?php

namespace Rate\CurrencyBundle\Tests;

use Rate\CurrencyBundle\DataProviders\Yahoo;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class YahooProviderTest extends KernelTestCase
{


    public function testGetRatesValues()
    {
        $currentDate = new \DateTime();
        $mockRequestResult = '{"query":{"count":2,"created":"' . $currentDate->format('Y-m-d\TH:i:s\Z') . '","lang":"en-US","results":{"rate":[{"id":"USDRUB","Name":"USD/RUB","Rate":"66.9315","Date":"5/23/2016","Time":"5:49pm","Ask":"66.9580","Bid":"66.9315"},{"id":"EURRUB","Name":"EUR/RUB","Rate":"75.0010","Date":"5/23/2016","Time":"5:49pm","Ask":"75.0055","Bid":"74.9965"}]}}}';
        $yahooService = $this->getMockedDataProvider($mockRequestResult);

        $currentDate->setTimezone(new \DateTimeZone(Yahoo::DEFAULT_TIMEZONE));

        $this->assertEquals([
            'date' => $currentDate->format(Yahoo::DATE_FORMAT),
            'rates' => [
                Yahoo::CURRENCY_USD => 66.9315,
                Yahoo::CURRENCY_EURO => 75.0010
            ]
        ], $yahooService->getRateValues());
    }

    /**
     * @expectedException \Rate\CurrencyBundle\Exceptions\LogicException
     */
    public function testInvalidFormatException()
    {
        $yahooService = $this->getMockedDataProvider('foobar'); // any invalid json string
        $yahooService->getRateValues();
    }

    /**
     * @param string $returnResult
     * @return \PHPUnit_Framework_MockObject_MockObject|Yahoo
     */
    protected function getMockedDataProvider($returnResult)
    {
        $yahooService = $this->getMockBuilder(Yahoo::class)
            ->setMethods(['getRequestContent'])
            ->disableOriginalConstructor()
            ->getMock();

        $yahooService->method('getRequestContent')
            ->willReturn($returnResult);

        return $yahooService;
    }
}