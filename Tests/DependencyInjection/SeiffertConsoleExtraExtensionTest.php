<?php

namespace Seiffert\ConsoleExtraBundle\Tests\DependencyInjection;

use PHPUnit_Framework_TestCase;
use Seiffert\ConsoleExtraBundle\DependencyInjection\SeiffertConsoleExtraExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Paul Seiffert <paul.seiffert@gmail.com>
 */
class SeiffertConsoleExtraExtensionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SeiffertConsoleExtraExtension;
     */
    private $extension;

    public function setUp()
    {
        $this->extension = new SeiffertConsoleExtraExtension();
    }

    public function testLoadAddsCommandRegistryDefinition()
    {
        $container = new ContainerBuilder();

        $this->extension->load(array(), $container);

        $this->assertTrue($container->hasDefinition('seiffert_console_extra.command_registry'));

        $definition = $container->getDefinition('seiffert_console_extra.command_registry');
        $class = $container->getParameter(str_replace('%', '', $definition->getClass()));

        $this->assertEquals('Seiffert\ConsoleExtraBundle\CommandRegistry', $class);
    }
}
