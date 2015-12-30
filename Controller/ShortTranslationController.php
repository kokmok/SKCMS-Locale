<?php

namespace SKCMS\LocaleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class ShortTranslationController extends Controller
{

    private function process($forceJson=0,$path = null,Request $request,$templateFile){
        $loader = $this->get('skcms_locale.translations.loader');
        $loader->loadTranslations();
        $loader->createForm($path);


        if ($request->getMethod() == 'POST'){
            $session = new Session();
            if($loader->bindForm($request)){
//                if ($forceJson){
//                    return new JsonResponse(['status'=>1,'callBackParams'=>['refresh'=>1]]);
//                }
//                else{

                    $session->getFlashBag()->add('success','short_translation.updated');
                    return $this->redirectToRoute($request->attributes->get('_route'),$request->attributes->get('_route_params'));
//                }

            }
            elseif($loader->getForm()->isSubmitted()){
                $session->getFlashBag()->add('error','short_translation.update_failed');
            }

        }

        $renderParams = ['translations'=>$loader->getTranslationTree(),'untranslatedTree'=>$loader->getUntranslatedTree(),'form'=>$loader->getForm()->createView()];
        if ($forceJson){
            return new JsonResponse(['status'=>1,'callBackParams'=>['view'=>$this->renderView($templateFile,$renderParams)]]);
        }
        else{
            return $this->render($templateFile,$renderParams);
        }
    }
    public function indexAction($forceJson=0,$path = null,Request $request)
    {
//        $loader = $this->get('skcms_locale.translations.loader');
//        $loader->loadTranslations();
//        $loader->createForm($path);
//
//
//        if ($request->getMethod() == 'POST'){
//            if($loader->bindForm($request)){
//
//                if ($forceJson){
//                    return new JsonResponse(['status'=>1,'callBackParams'=>['refresh'=>1]]);
//                }
//                else{
//                    return $this->redirectToRoute($request->attributes->get('_route'));
//                }
//
//            }
//
//        }
//
//        $renderParams = ['translations'=>$loader->getTranslationTree(),'form'=>$loader->getForm()->createView()];
//
//        if ($forceJson){
//            return new JsonResponse(['status'=>1,'callBackParams'=>['view'=>$this->renderView($templateFile,$renderParams)]]);
//        }
//        else{
//            return $this->render($templateFile,$renderParams);
//        }
        return $this->process($forceJson,$path,$request,'SKCMSLocaleBundle:translations:translations.html.twig');

    }
    public function formAction($forceJson=0,$path = null,Request $request)
    {
        return $this->process($forceJson,$path,$request,'SKCMSLocaleBundle:translations:form.html.twig');
    }
}
