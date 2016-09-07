<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunSlackBotCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('slackbot:run')
            ->setDescription('Run Slackbot')
            ->addArgument('token', InputArgument::REQUIRED, 'Token')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $loop = \React\EventLoop\Factory::create();

        $client = new \Slack\RealTimeClient($loop);
        $client->setToken($input->getArgument('token'));

        $client->on('message', function ($data) use ($client) {
            if ($data['text'] == 'ping') {
                $client->getChannelById($data['channel'])->then(function (\Slack\Channel $channel) use ($client) {
                    $client->send('pong!', $channel);
                });
            }
        });

        $client->connect()->then(function () {
            echo "Connected!\n";
        });

        $loop->run();
    }
}
