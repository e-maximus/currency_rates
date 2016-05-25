<?php

namespace Rate\CurrencyBundle\Tests;

use Doctrine\ORM\EntityManager;
use PHPUnit_Framework_MockObject_MockObject;
use Rate\CurrencyBundle\CurrencyInterface\ExchangeRateProvider;
use Rate\CurrencyBundle\DataProviders\Yahoo;
use Rate\CurrencyBundle\Entity\RateCurrent;
use Rate\CurrencyBundle\Services\ExchangeCurrencies;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use Symfony\Component\Console\Input\StringInput;

class ExchangeCurrenciesTest extends KernelTestCase
{

    /** @var  EntityManager */
    protected static $entityManager;

    protected static $application;

    public static function setUpBeforeClass()
    {
        self::runCommand('doctrine:database:drop --force');
        self::runCommand('doctrine:database:create');
        self::runCommand('doctrine:schema:update --force');

        static::$entityManager = static::getApplication()->getKernel()->
        getContainer()->get('doctrine')->getManager();
    }
    
    protected static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);

        return self::getApplication()->run(new StringInput($command));
    }

    protected static function getApplication()
    {
        if (null === self::$application) {
            static::bootKernel();

            self::$application = new Application(static::$kernel);
            self::$application->setAutoExit(false);
        }

        return self::$application;
    }

    /**
     * @dataProvider exchangeRateProvider
     *
     * @param $rateUSD
     * @param $rateEURO
     */
    public function testInitialUpdateValues($rateUSD, $rateEURO)
    {

        /** @var PHPUnit_Framework_MockObject_MockObject|Yahoo $yahooService */
        $yahooService = $this->getMockBuilder(Yahoo::class)
            ->setMethods(['getRateValues'])
            ->disableOriginalConstructor()
            ->getMock();
        $yahooService->method('getRateValues')
            ->willReturn([
                'date' => date(Yahoo::DATE_FORMAT),
                'rates' => [
                    Yahoo::CURRENCY_USD => $rateUSD,
                    Yahoo::CURRENCY_EURO => $rateEURO,
                ]
            ]);

        /** @var ExchangeCurrencies $exchangeService */
        $exchangeService = new ExchangeCurrencies($yahooService, $this->getEntityManager());
        $exchangeService->updateCurrencyRates();
        
        $this->getEntityManager()->clear();

        /** @var RateCurrent $currencyUSD */
        $currencyUSD = $this->getEntityManager()->getRepository(RateCurrent::class)->findOneBy([
            'name' => ExchangeRateProvider::CURRENCY_USD
        ]);
        $this->assertEquals($rateUSD, $currencyUSD->getRate());

        /** @var RateCurrent $currencyEURO */
        $currencyEURO = $this->getEntityManager()->getRepository(RateCurrent::class)->findOneBy([
            'name' => ExchangeRateProvider::CURRENCY_EURO
        ]);
        $this->assertEquals($rateEURO, $currencyEURO->getRate());
    }


    /**
     * @return array
     */
    public function exchangeRateProvider()
    {
        return array(
            array(10.1234, 20.4321),
            array(11.1111, 22.2222),
            array(75.2322, 87.0002)
        );
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager() 
    {
        return static::$entityManager;
    }

    public static function tearDownAfterClass()
    {
        self::$entityManager = null;
        self::$application = null;
    }
}