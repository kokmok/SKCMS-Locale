<?php
namespace SKCMS\LocaleBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use SKCMS\UserBundle\Entity\Country;

class ImportCurrenciesCommand extends ContainerAwareCommand 
{
    
    const CURRENCIES_FILE = '/../Resources/public/datas/currencies.csv';
    
    
    protected function configure()
    {
        $this
            ->setName('skcms:import:currencies')
            ->setDescription('Import currencies from csv file')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $nombreDevies = $this->createCurrencies();
        $output->writeln($nombreDevies.' currencies successfully added');
    }
    
    public function createCurrencies()
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $csvCurrencies = array_map('str_getcsv', file(__DIR__.self::CURRENCIES_FILE));
        
        $currencyAdded = [];
        foreach ($csvCurrencies as $csvCurrency)
        {
            if (!in_array($csvCurrency['2'], $currencyAdded))
            {
                $currencyAdded[] = $csvCurrency['2'];
                
                $currency = new \SKCMS\LocaleBundle\Entity\Currency();
                
                $currency->setName($csvCurrency['1']);
                $currency->setIso($csvCurrency['2']);
                
                $em->persist($currency);
            }
            
        }
        
        $em->flush();
        
        return count($currencyAdded);
        
    }
}
