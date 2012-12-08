<?php

namespace Seiffert\ConsoleExtraBundle;

use Symfony\Component\Console\Command\Command;

/**
 * @author Paul Seiffert <paul.seiffert@gmail.com>
 */
class CommandRegistry
{
    /**
     * @var array|Command[]
     */
    private $commands = array();

    /**
     * @param Command $command
     */
    public function registerCommand(Command $command)
    {
        $this->commands[] = $command;
    }

    /**
     * @return array|Command[]
     */
    public function getCommands()
    {
        return $this->commands;
    }
}
