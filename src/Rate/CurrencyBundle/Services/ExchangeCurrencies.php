<?php

namespace Rate\CurrencyBundle\Services;

use Doctrine\ORM\EntityManager;
use Rate\CurrencyBundle;

class ExchangeCurrencies
{
    /** @var  CurrencyBundle\CurrencyInterface\ExchangeRateProvider */
    protected $dataProvider;

    /** @var  EntityManager */
    protected $entityManager;
    
    
    public function __construct(
        CurrencyBundle\CurrencyInterface\ExchangeRateProvider $dataProvider,
        EntityManager $entityManager
    ) {
        $this->setDataProvider($dataProvider);
        $this->setEntityManager($entityManager);
    }

    /**
     * @param string|null $currencies
     * @return void
     */
    public function updateCurrencyRates($currencies = null)
    {
        if (empty($currencies)) {
            $currencies = CurrencyBundle\CurrencyInterface\ExchangeRateProvider::DEFAULT_CURRENCIES;
        }

        $currencyRates = $this->getDataProvider()->getRateValues($currencies);
        $allRates = $this->getEntityManager()->getRepository(CurrencyBundle\Entity\RateCurrent::class)->findAll();

        $findByName = function ($currencyName) use ($allRates) {
            if (!is_array($allRates) && !count($allRates)) {
                return null;
            }
            /** @var CurrencyBundle\Entity\RateCurrent $rate */
            foreach ($allRates as $rate) {
                if ($rate->getName() === $currencyName) {
                    return $rate;
                }
            }

            return null;
        };

        foreach ($currencyRates['rates'] as $name => $rate) {
            $rateEntity = $findByName($name);
            if (!$rateEntity) {
                $rateEntity = new CurrencyBundle\Entity\RateCurrent();
                $rateEntity->setName($name);
            }

            $rateEntity->setRate($rate);
            $this->getEntityManager()->persist($rateEntity);
            $this->getEntityManager()->flush($rateEntity);
        }

        $this->addToArchive($currencyRates);
    }

    /**
     * @param array $currencyRates
     * @return bool
     */
    protected function addToArchive(array $currencyRates)
    {
        if (!is_array($currencyRates['rates']) || !count($currencyRates['rates'])) {
            return false;
        }

        foreach ($currencyRates['rates'] as $name => $rate) {
            $archiveRate = new CurrencyBundle\Entity\RateArchive();
            $archiveRate->setName($name);
            $archiveRate->setRate($rate);
            $archiveRate->setCreatedAt(new \DateTime($currencyRates['date']));

            $this->getEntityManager()->persist($archiveRate);
            $this->getEntityManager()->flush($archiveRate);
        }

        return true;
    }

    /**
     * @return CurrencyBundle\CurrencyInterface\ExchangeRateProvider
     */
    protected function getDataProvider()
    {
        return $this->dataProvider;
    }

    /**
     * @param CurrencyBundle\CurrencyInterface\ExchangeRateProvider $dataProvider
     */
    protected function setDataProvider($dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }
    
    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManager $entityManager
     */
    protected function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
