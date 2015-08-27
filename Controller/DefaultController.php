<?php

namespace SKCMS\LocaleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('LELocaleBundle:Default:index.html.twig', array('name' => $name));
    }
}
