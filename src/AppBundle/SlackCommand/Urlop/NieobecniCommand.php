<?php

namespace AppBundle\SlackCommand\Urlop;

use AppBundle\SlackCommand\AbstractCommand;
use Slack\Channel;
use Slack\User;
use Spatie\Regex\Regex;

class NieobecniCommand extends AbstractCommand
{
    public function execute(string $message, User $user, Channel $channel)
    {
        parent::execute($message, $user, $channel);

        $nieobecniRegex = Regex::match('/nieobecni (.+)/', $message);

        if ($nieobecniRegex->hasMatch()) {
            preg_match('/nieobecni(?<date>.+)/', $message, $results);
            
            $period = $this->getPeriod($results['date']);

            $this->reply($period['startDate']->format('d.m.Y') . ' są wszyscy');
        }
    }
}
