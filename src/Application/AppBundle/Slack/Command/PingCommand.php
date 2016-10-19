<?php

namespace Application\AppBundle\Slack\Command;

class PingCommand extends AbstractCommand
{
    public function configure()
    {
        $this->setRegex('/ping/');
    }

    public function execute(CommandInput $input, CommandOutput $output)
    {
        $output->setText('pong!');
    }
}
