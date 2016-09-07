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
                $username = $client->getUserById($data['user'])->then(function ($user) {
                    return $user->getUsername();
                });

                $channel = $client->getChannelById($data['channel'])->then(function (\Slack\Channel $channel) use ($client) {
                    return $channel;
                });

                \React\Promise\all([$username, $channel])->then(function ($data) use ($client) {
                    $client->send('@' . $data[0] . ' pong!', $data[1]);
                });
            }
        });

        $client->connect()->then(function () {
            echo "Connected!\n";
        });

        $loop->run();
    }
}
