<?php

namespace Application\Slack;

class Matcher
{
    private $commands;

    public function registerCommands($commands)
    {
        $this->commands = $commands;
    }

    public function matchCommand($message)
    {
        foreach ($this->commands as $command)
        {
            $command->configure();

            if($command->matches($message))
                return $command;
        }
    }
}
