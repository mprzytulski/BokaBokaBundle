<?php
/**
 * @author Michal Przytulski <michal@przytulski.pl>
 * @since 09.09.2012
 */

namespace Aurora\BokaBokaBundle\Messaging\RabbitMQ\Connection;

use \Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use \Symfony\Component\DependencyInjection\ContainerBuilder;
use \Symfony\Component\DependencyInjection\Reference;

class ConnectionCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('boka_boka.connection_manager')) {
            return;
        }

        $definition = $container->getDefinition('boka_boka.connection_manager');

        foreach ($container->findTaggedServiceIds('boka_boka.connection') as $id => $attributes) {
            $definition->addMethodCall('registryConnection', array($id, new Reference($id)));
        }
    }
}