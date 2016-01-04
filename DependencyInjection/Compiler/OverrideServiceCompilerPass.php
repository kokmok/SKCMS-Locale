<?php
/**
 * Created by jona on 4/01/16
 */

namespace SKCMS\LocaleBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideServiceCompilerPass implements CompilerPassInterface
{

    private $logging;


    public function process(ContainerBuilder $container)
    {
        if (null == $this->logging){
            return $this->storeLogging($container);
        }

        $this->logging->setClass('SKCMS\LocaleBundle\Logging\LoggingTranslator');
        if ($container->hasDefinition('debug.event_dispatcher')){
            $eventDispatcher = $container->getDefinition('debug.event_dispatcher');
        }
        elseif ($container->hasDefinition('event_dispatcher')){
            $eventDispatcher = $container->getDefinition('event_dispatcher');
        }
        


        $this->logging->addMethodCall('setEventDispatcher',[$eventDispatcher]);
    }

    private function storeLogging(ContainerBuilder $container){
        $this->logging = $container->getDefinition('translator.logging');
    }
}