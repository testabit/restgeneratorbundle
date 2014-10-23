<?php

namespace Voryx\RESTGeneratorBundle;

use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Voryx\RESTGeneratorBundle\DependencyInjection\Compiler\ManagerCompiler;

class VoryxRESTGeneratorBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ManagerCompiler(), PassConfig::TYPE_REMOVE);
    }
}
