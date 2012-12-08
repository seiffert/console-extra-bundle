<?php

namespace Seiffert\ConsoleExtraBundle\Tests;

use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Seiffert\ConsoleExtraBundle\SeiffertConsoleExtraBundle;
use Seiffert\ConsoleExtraBundle\CommandRegistry;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Paul Seiffert <paul.seiffert@gmail.com>
 * @covers Seiffert\ConsoleExtraBundle\SeiffertConsoleExtraBundle
 */
class SeiffertConsoleExtraBundleTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SeiffertConsoleExtraBundle
     */
    private $bundle;

    /**
     * Creates the bundle to test.
     */
    public function setUp()
    {
        $this->bundle = new SeiffertConsoleExtraBundle();
    }

    /**
     * Tests whether commands are read from the command registry when being asked to register commands at the
     * application.
     */
    public function testRegisterCommandsLoadsCommandsFromRegistry()
    {
        $container = $this->getMockContainer();
        $registry = $this->getMockCommandRegistry();
        $application = $this->getMockApplication();
        $commands = $this->getTestCommands();

        $bundle = new SeiffertConsoleExtraBundle();
        $bundle->setContainer($container);

        $container->expects($this->once())
                  ->method('get')
                  ->with('seiffert_console_extra.command_registry')
                  ->will($this->returnValue($registry));

        $registry->expects($this->once())
                 ->method('getCommands')
                 ->will($this->returnValue($commands));

        $application->expects($this->once())
                    ->method('addCommands')
                    ->with($commands);

        $bundle->registerCommands($application);
    }

    public function testBuildAddsCompilerPass()
    {
        $container = $this->getMockContainerBuilder();

        $container->expects($this->once())
                  ->method('addCompilerPass')
                  ->with(
                $this->isInstanceOf(
                    'Seiffert\ConsoleExtraBundle\DependencyInjection\CompilerPass\CommandCompilerPass'));

        $this->bundle->build($container);
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject|Container
     */
    private function getMockContainer()
    {
        return $this->getMock('Symfony\Component\DependencyInjection\Container', array('get'));
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject|CommandRegistry
     */
    private function getMockCommandRegistry()
    {
        return $this->getMock('Seiffert\ConsoleExtraBundle\CommandRegistry', array('getCommands'));
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject|Application
     */
    private function getMockApplication()
    {
        return $this->getMock('Symfony\Component\Console\Application', array('addCommands'));
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject|ContainerBuilder
     */
    private function getMockContainerBuilder()
    {
        return $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder', array('addCompilerPass'));
    }

    /**
     * @return array|Command[]
     */
    private function getTestCommands()
    {
        return array(new Command('test-command-1'), new Command('test-command-2'));
    }

}