<?php

/**
 * Created by jona on 30/12/15
 */
namespace SKCMS\LocaleBundle\Twig;

use SKCMS\LocaleBundle\Loader\TranslationFilesLoader;

class LocaleExtension extends \Twig_Extension
{

    private $container;
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('SKCMSLocaleTranslationDecode', array($this, 'SKCMSLocaleTranslationDecode')),
            new \Twig_SimpleFilter('localeToName', array($this, 'localeToName'))
        ];
    }
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('skcmsEnabledLocales', array($this, 'skcmsEnabledLocales'))
        ];
    }

    public function skcmsEnabledLocales(){
        return $this->container->getParameter('skcms.locale.locales');
    }

    public function SKCMSLocaleTranslationDecode($input){
        return TranslationFilesLoader::stringSanitizer($input,true);
    }


    public function localeToName($locale){
        $table = [
            'fr'=>'Français',
            'en'=>'English',
            'de'=>'Deutsch',
            'nl'=>'Nederlands',
        ];

            return isset($table[$locale])? $table[$locale] : $locale;

    }

    public function getName()
    {
        return 'skcms.locale.extrension';
    }
}