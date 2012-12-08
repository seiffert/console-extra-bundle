<?php

namespace Seiffert\ConsoleExtraBundle;

use Seiffert\ConsoleExtraBundle\DependencyInjection\CompilerPass\CommandCompilerPass;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Paul Seiffert <paul.seiffert@gmail.com>
 */
class SeiffertConsoleExtraBundle extends Bundle
{
    /**
     * @param Application $application
     */
    public function registerCommands(Application $application)
    {
        parent::registerCommands($application);

        $commandRegistry = $this->container->get('seiffert_console_extra.command_registry');
        $application->addCommands($commandRegistry->getCommands());
    }

    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new CommandCompilerPass());
    }
}
