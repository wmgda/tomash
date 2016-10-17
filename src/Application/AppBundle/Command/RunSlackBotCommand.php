<?php

namespace Application\AppBundle\Command;

use Application\AppBundle\SlackCommand\Lunch\IAmEatingCommand;
use Application\AppBundle\SlackCommand\Lunch\WeAreEatingCommand;
use Application\AppBundle\SlackCommand\Lunch\SumUpCommand;
use Application\AppBundle\SlackCommand\Lunch\VindicateCommand;
use Application\AppBundle\SlackCommand\Lunch\CloseCommand;
use Application\AppBundle\SlackCommand\PingCommand;
use Application\AppBundle\SlackCommand\Absence\WhereIsCommand;
use Application\AppBundle\SlackCommand\Absence\WhoIsAbsentCommand;
use Application\AppBundle\SlackCommand\Absence\AbsenceCommand;
use Application\Slack\Matcher;
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
            ->addArgument('botid', InputArgument::REQUIRED, 'Bot ID')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $loop = \React\EventLoop\Factory::create();

        $client = new \Slack\RealTimeClient($loop);
        $client->setToken($input->getArgument('token'));

        $botId = $input->getArgument('botid');

        $client->on('message', function ($data) use ($client, $botId) {
            if (!$data['text']) {
                return;
            }

            $message = $this->parseMessage($data['text'], $botId);

            if (!$message) {
                return;
            }

            $commands = [
//                new PingCommand($client),
                new WeAreEatingCommand($client),
                new IAmEatingCommand($client),
                new SumUpCommand($client),
                new VindicateCommand($client),
//                new AbsenceCommand($client),
//                new WhereIsCommand($client),
//                new WhoIsAbsentCommand($client),
                new CloseCommand($client),
            ];

            $username = $client->getUserById($data['user'])->then(function ($user) {
                return $user;
            });

            $channel = $client->getChannelById($data['channel'])->then(function (\Slack\Channel $channel) use ($client) {
                return $channel;
            });

            $matcher = new Matcher();
            $matcher->registerCommands($commands);

            \React\Promise\all([$username, $channel])->then(function ($data) use ($matcher, $message) {
                $command = $matcher->matchCommand($message);

                if($command) {
                    $command->execute($message, $data[0], $data[1]);
                }
            });
        });

        $client->connect()->then(function () {
            echo "Connected!\n";
        });

        $loop->run();
    }

    protected function parseMessage(string $message, string $botId)
    {
//        $matches = preg_match('/\<\@U261PFXBM\>:?(?<text>.+)/', $message, $results);
        $matches = preg_match('/\<\@' . $botId . '\>:?(?<text>.+)/', $message, $results);

        if (!$matches) {
            return false;
        }

        return trim($results['text']);
    }
}