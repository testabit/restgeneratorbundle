<?php

namespace Voryx\RESTGeneratorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ManagerCompiler implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $managerFactoryServiceId = 'voryx.manager.service_factory';

        if (!$container->hasDefinition($managerFactoryServiceId)) {
            return;
        }

        $taggedServices = $container->findTaggedServiceIds('voryx.manager');
        foreach ($taggedServices as $id => $tagAttributes) {
            $container
                ->getDefinition($id)
                ->setConfigurator(array(
                    new Reference($managerFactoryServiceId),
                    'addManager'
                ));
        }
    }

} 