<?php

namespace Seiffert\ConsoleExtraBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Paul Seiffert <paul.seiffert@gmail.com>
 */
class CommandCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('seiffert_console_extra.command_registry')) {
            return;
        }

        $commandRegistryDef = $container->getDefinition('seiffert_console_extra.command_registry');

        $commandServiceIds = $container->findTaggedServiceIds('console.command');

        foreach ($commandServiceIds as $id => $attributes) {
            $commandRegistryDef->addMethodCall('registerCommand', array(new Reference($id)));
        }
    }
}
