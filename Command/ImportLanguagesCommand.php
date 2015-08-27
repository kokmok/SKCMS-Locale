<?php
namespace SKCMS\LocaleBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use SKCMS\UserBundle\Entity\Country;

class ImportLanguagesCommand extends ContainerAwareCommand 
{
    
    const LANGUAGES_FILE = '/../Resources/public/datas/languages.json';
    
    
    protected function configure()
    {
        $this
            ->setName('skcms:import:languages')
            ->setDescription('Import languages from json file')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $nombrePays = $this->createLanguages();
        $output->writeln($nombrePays.' languages successfully added');
    }
    
    public function createLanguages()
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $jsonLanguages = json_decode(file_get_contents(__DIR__.self::LANGUAGES_FILE),true);
        
        
        
        foreach ($jsonLanguages as $jsonLanguageISO => $jsonLanguageData )
        {
            $language = new \SKCMS\LocaleBundle\Entity\Language();
            
            $names = explode(',',$jsonLanguageData['name']);
            $nativeNames = explode(',',$jsonLanguageData['nativeName']);
            
            $language->setName($names[0]);
            $language->setNativeName($nativeNames[0]);
            $language->setISO($jsonLanguageISO);
            $em->persist($language);
        }
        
        $em->flush();
        
        return count($jsonLanguages);
        
    }
}
