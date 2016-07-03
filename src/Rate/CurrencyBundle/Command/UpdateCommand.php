<?php

namespace Rate\CurrencyBundle\Command;

use Rate\CurrencyBundle\Services\ExchangeCurrencies;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('currencies:update')
            ->setDescription('Update currencies data')
            ->addArgument(
                'currencies',
                InputArgument::OPTIONAL,
                'List of currencies would you like to update'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $currencies = $input->getArgument('currencies');

        /** @var ExchangeCurrencies $currencyService */
        $currencyService = $this->getContainer()->get('exchange_currencies_loader');
        $currencyService->updateCurrencyRates($currencies);
    }
}
