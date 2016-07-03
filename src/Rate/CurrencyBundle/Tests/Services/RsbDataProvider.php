<?php

namespace Rate\CurrencyBundle\Tests;

use Rate\CurrencyBundle\DataProviders\Rsb;
use Rate\CurrencyBundle\DataProviders\Yahoo;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RsbProviderTest extends KernelTestCase
{


    public function testGetRatesValues()
    {
        $currentDate = new \DateTime(date("Y-m-d"));
        $mockRequestResult = '<?xml version="1.0" encoding="windows-1251" ?>
<ValCurs Date="' . $currentDate->format("Y-m-d") . '" name="Foreign Currency Market">
<Valute ID="R01215">
	<NumCode>208</NumCode>
	<CharCode>DKK</CharCode>
	<Nominal>10</Nominal>
	<Name>Датских крон</Name>
	<Value>98,7278</Value>
</Valute>
<Valute ID="R01235">
	<NumCode>840</NumCode>
	<CharCode>USD</CharCode>
	<Nominal>1</Nominal>
	<Name>Доллар США</Name>
	<Value>65,8949</Value>
</Valute>
<Valute ID="R01239">
	<NumCode>978</NumCode>
	<CharCode>EUR</CharCode>
	<Nominal>1</Nominal>
	<Name>Евро</Name>
	<Value>73,4596</Value>
</Valute>

</ValCurs>
';
        $rsbService = $this->getMockedDataProvider($mockRequestResult);

        $this->assertEquals([
            'date' => $currentDate->format(Rsb::DATE_FORMAT),
            'rates' => [
                Yahoo::CURRENCY_USD => 65.8949,
                Yahoo::CURRENCY_EURO => 73.4596
            ]
        ], $rsbService->getRateValues());
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
     * @return \PHPUnit_Framework_MockObject_MockObject|Rsb
     */
    protected function getMockedDataProvider($returnResult)
    {
        $yahooService = $this->getMockBuilder(Rsb::class)
            ->setMethods(['getRequestContent'])
            ->disableOriginalConstructor()
            ->getMock();

        $yahooService->method('getRequestContent')
            ->willReturn($returnResult);

        return $yahooService;
    }
}
