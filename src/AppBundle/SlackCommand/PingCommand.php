<?php

namespace AppBundle\SlackCommand;

use Slack\Channel;
use Slack\User;

class PingCommand extends AbstractCommand
{
    public function execute(string $message, User $user, Channel $channel)
    {
        if ($message == 'ping') {
            $this->client->send('@' . $user->getUsername() . ' pong!', $channel);

            return true;
        }

        return false;
    }
}
