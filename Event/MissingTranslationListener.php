<?php
/**
 * Created by jona on 4/01/16
 */

namespace SKCMS\LocaleBundle\Event;


use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\Writer\TranslationWriter;

class MissingTranslationListener
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var TranslationWriter
     */
    private $writer;

    private $container;

    public function __construct($container)
    {
        $this->request = $container->get('request');
        $this->entityManager = $container->get('doctrine')->getManager();
        $this->writer = $container->get('translation.writer');
        $this->container = $container;
    }

    public function missingEvent(MissingTranslationEvent $event){
        return;
        $bundleName = $this->getBundleName($this->request->attributes->get('_controller'));

        $kernel = $this->container->get('kernel');

        // Define Root Path to App folder
        $transPaths = array($kernel->getRootDir().'/Resources/');
        $currentName = 'app folder';

        // Override with provided Bundle info
        if (null !== $bundleName) {
            try {
                $foundBundle = $kernel->getBundle($bundleName);
                $transPaths = array(
                    $foundBundle->getPath().'/Resources/',
                    sprintf('%s/Resources/%s/', $kernel->getRootDir(), $foundBundle->getName()),
                );
                $currentName = $foundBundle->getName();
            } catch (\InvalidArgumentException $e) {
                // such a bundle does not exist, so treat the argument as path
                $transPaths = array($bundleName.'/Resources/');
                $currentName = $transPaths[0];

                if (!is_dir($transPaths[0])) {
//                    throw new \InvalidArgumentException(sprintf('<error>"%s" is neither an enabled bundle nor a directory.</error>', $transPaths[0]));
                }
            }

            // load any existing messages from the translation files
            $currentCatalogue = new MessageCatalogue($event->getLocale());
            $loader = $this->container->get('translation.loader');
            foreach ($transPaths as $path) {
                $path .= 'translations';
                if (is_dir($path)) {
                    $loader->loadMessages($path, $currentCatalogue);
                }
            }

            $currentCatalogue->set($event->getId(),'__'.$event->getId(),$event->getDomain());

            $bundleTransPath = false;
            foreach ($transPaths as $path) {
                $path .= 'translations';
                if (is_dir($path)) {
                    $bundleTransPath = $path;
                }
            }

            if (!$bundleTransPath) {
                $bundleTransPath = end($transPaths).'translations';
            }

            $this->writer->writeTranslations($currentCatalogue, 'yml', array('path' => $bundleTransPath, 'default_locale' => $this->container->getParameter('kernel.default_locale')));

        }
    }

    private function getBundleName($controllerNameSpace){
        $bundleName = "";
        $exploded = explode('\\',$controllerNameSpace);
        foreach ($exploded as $element){
            $bundleName.= $element;
            if (preg_match('#Bundle$#',$element)){
                break;
            }
        }
        return $bundleName;
    }
}