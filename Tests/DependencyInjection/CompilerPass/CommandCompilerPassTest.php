<?php

namespace Seiffert\ConsoleExtraBundle\Tests\DependencyInjection\CompilerPass;

use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use Seiffert\ConsoleExtraBundle\DependencyInjection\CompilerPass\CommandCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Paul Seiffert <paul.seiffert@gmail.com>
 */
class CommandCompilerPassTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var CommandCompilerPass
     */
    private $compilerPass;

    /**
     * Instantiates a command compiler pass.
     */
    public function setUp()
    {
        $this->compilerPass = new CommandCompilerPass();
    }

    public function testProcessAsksForRegistryDefinition()
    {
        $container = $this->getMockContainerBuilder();

        $container->expects($this->once())
            ->method('hasDefinition')
            ->with('seiffert_console_extra.command_registry')
            ->will($this->returnValue(false));

        $this->compilerPass->process($container);
    }

    public function testProcessRetrievesRegistryDefinition()
    {
        $container = $this->getMockContainerBuilder();

        $container->expects($this->once())
            ->method('hasDefinition')
            ->with('seiffert_console_extra.command_registry')
            ->will($this->returnValue(true));
        $container->expects($this->once())
            ->method('getDefinition')
            ->with('seiffert_console_extra.command_registry')
            ->will($this->throwException(new \Exception()));

        $this->setExpectedException('Exception');
        $this->compilerPass->process($container);
    }

    public function testProcessRetrievesTaggedServiceIds()
    {
        $container = $this->getMockContainerBuilder();

        $container->expects($this->once())
            ->method('hasDefinition')
            ->with('seiffert_console_extra.command_registry')
            ->will($this->returnValue(true));
        $container->expects($this->once())
            ->method('getDefinition')
            ->with('seiffert_console_extra.command_registry')
            ->will($this->returnValue(new Definition()));

        $container->expects($this->once())
            ->method('findTaggedServiceIds')
            ->will($this->throwException(new \Exception()));

        $this->setExpectedException('Exception');
        $this->compilerPass->process($container);
    }

    public function testProcessAddsMethodCallsToRegistryDef()
    {
        $test = $this;
        $container = $this->getMockContainerBuilder();
        $serviceIds = array('test_id' => array(), 'another_test_id' => array());
        $registryDef = $this->getMock('Symfony\Component\DependencyInjection\Definition', array('addMethodCall'));

        $container->expects($this->once())
            ->method('hasDefinition')
            ->with('seiffert_console_extra.command_registry')
            ->will($this->returnValue(true));
        $container->expects($this->once())
            ->method('getDefinition')
            ->with('seiffert_console_extra.command_registry')
            ->will($this->returnValue($registryDef));

        $container->expects($this->once())
            ->method('findTaggedServiceIds')
            ->will($this->returnValue($serviceIds));

        $registryDef->expects($this->exactly(count($serviceIds)))
                    ->method('addMethodCall')
                    ->with(
                'registerCommand',
                $this->callback(
                    function ($args) use ($serviceIds, $test) {
                        $test->assertCount(1, $args);
                        $reference = array_pop($args);
                        $test->assertInstanceOf('Symfony\Component\DependencyInjection\Reference', $reference);

                        return in_array((string)$reference, array_keys($serviceIds));
                    }
                ));

        $this->compilerPass->process($container);
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject|ContainerBuilder
     */
    private function getMockContainerBuilder()
    {
        return $this->getMock(
            'Symfony\Component\DependencyInjection\ContainerBuilder',
            array('hasDefinition', 'getDefinition', 'findTaggedServiceIds'));
    }
}
