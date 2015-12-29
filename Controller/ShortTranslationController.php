<?php

namespace SKCMS\LocaleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShortTranslationController extends Controller
{
    public function indexAction()
    {
        $loader = $this->get('skcms_locale.files.loader');
        $loader->loadTranslations();

        dump($loader->getTranslationTree());
        return $this->render('SKCMSLocaleBundle:translations:translations.html.twig',['translations'=>$loader->getTranslationTree()]);

    }
}
