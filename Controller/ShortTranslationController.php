<?php

namespace SKCMS\LocaleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ShortTranslationController extends Controller
{
    public function indexAction($forceJson=0,$path = null,Request $request)
    {
        $loader = $this->get('skcms_locale.translations.loader');
        $loader->loadTranslations();
        $loader->createForm($path);


        if ($request->getMethod() == 'POST'){
            if($loader->bindForm($request)){
                if ($forceJson){
                    return new JsonResponse(['status'=>1,'callBackParams'=>['refresh'=>1]]);
                }
                else{
                    return $this->redirectToRoute($request->attributes->get('_route'));
                }

            }
        }

        $renderParams = ['translations'=>$loader->getTranslationTree(),'form'=>$loader->getForm()->createView()];
        $templateFile = 'SKCMSLocaleBundle:translations:translations.html.twig';
        if ($forceJson){
            return new JsonResponse(['status'=>1,'callBackParams'=>['view'=>$this->renderView($templateFile,$renderParams)]]);
        }
        else{
            return $this->render($templateFile,$renderParams);
        }


    }
    public function formAction($forceJson=0,$path = null,Request $request)
    {
        $loader = $this->get('skcms_locale.translations.loader');
        $loader->loadTranslations();
        $loader->createForm($path);


        if ($request->getMethod() == 'POST'){
            if($loader->bindForm($request)){
                if ($forceJson){
                    return new JsonResponse(['status'=>1,'callBackParams'=>['refresh'=>1]]);
                }
                else{
                    return $this->redirectToRoute($request->attributes->get('_route'));
                }

            }
        }

        $renderParams = ['translations'=>$loader->getTranslationTree(),'form'=>$loader->getForm()->createView()];
        $templateFile = 'SKCMSLocaleBundle:translations:form.html.twig';
        if ($forceJson){
            return new JsonResponse(['status'=>1,'callBackParams'=>['view'=>$this->renderView($templateFile,$renderParams)]]);
        }
        else{
            return $this->render($templateFile,$renderParams);
        }


    }
}
