<?php

namespace SKCMS\LocaleBundle;

use SKCMS\LocaleBundle\DependencyInjection\Compiler\OverrideServiceCompilerPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SKCMSLocaleBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $compilerPass = new OverrideServiceCompilerPass();
        $container->addCompilerPass($compilerPass,PassConfig::TYPE_BEFORE_OPTIMIZATION);
        $container->addCompilerPass($compilerPass,PassConfig::TYPE_AFTER_REMOVING);
    }
}
