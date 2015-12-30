<?php

/**
 * Created by jona on 30/12/15
 */
namespace SKCMS\LocaleBundle\Twig;

use SKCMS\LocaleBundle\Loader\TranslationFilesLoader;

class LocaleExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('SKCMSLocaleTranslationDecode', array($this, 'SKCMSLocaleTranslationDecode'))
        ];
    }

    public function SKCMSLocaleTranslationDecode($input){
        return TranslationFilesLoader::stringSanitizer($input,true);
    }


    public function getName()
    {
        return 'skcms.locale.extrension';
    }
}