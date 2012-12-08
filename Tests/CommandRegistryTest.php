<?php

namespace Seiffert\ConsoleExtraBundle\Tests;

use PHPUnit_Framework_TestCase;
use Seiffert\ConsoleExtraBundle\CommandRegistry;
use Symfony\Component\Console\Command\Command;

/**
 * @author Paul Seiffert <paul.seiffert@gmail.de>
 * @covers Seiffert\ConsoleExtraBundle\CommandRegistry
 */
class CommandRegistryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var CommandRegistry
     */
    private $registry;

    public function setUp()
    {
        $this->registry = new CommandRegistry();
    }

    public function testGetCommandsEmpty()
    {
        $commands = $this->registry->getCommands();

        $this->assertEmpty($commands);
    }

    public function testRegisterCommand()
    {
        $command = new Command('test:command');
        $this->registry->registerCommand($command);

        $registryCommands = $this->registry->getCommands();
        $this->assertCount(1, $registryCommands);
        $this->assertSame($command, array_pop($registryCommands));
    }

    public function testRegisterMultipleCommands()
    {
        $this->registry->registerCommand(new Command('test1'));
        $this->registry->registerCommand(new Command('test2'));

        $this->assertCount(2, $this->registry->getCommands());
    }
}