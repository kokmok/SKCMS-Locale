<?php

namespace SKCMS\LocaleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ShortTranslationController extends Controller
{
    public function indexAction($path = null,Request $request)
    {
        $loader = $this->get('skcms_locale.translations.loader');
        $loader->loadTranslations();
        $loader->createForm($path);


        if ($request->getMethod() == 'POST'){
            if($loader->bindForm($request)){
                return $this->redirectToRoute($request->attributes->get('_route'));
            }
        }

        return $this->render('SKCMSLocaleBundle:translations:translations.html.twig',['translations'=>$loader->getTranslationTree(),'form'=>$loader->getForm()->createView()]);

    }
}
