<?php

namespace AppBundle\SlackCommand\Urlop;

use AppBundle\SlackCommand\AbstractCommand;
use Slack\Channel;
use Slack\User;
use Spatie\Regex\Regex;

class GdzieJestCommand extends AbstractCommand
{
    public function execute(string $message, User $user, Channel $channel)
    {
        parent::execute($message, $user, $channel);

        $gdzieJestRegex = Regex::match('/gdzie jest (.+)/', $message);

        if ($gdzieJestRegex->hasMatch()) {
            preg_match('/gdzie jest(?<name>.+)/', $message, $results);
            $name = trim($results['name']);

            $this->reply($name . ' jest dzisiaj w domu');
        }
    }
}
