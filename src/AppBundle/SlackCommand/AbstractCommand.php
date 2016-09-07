<?php

namespace AppBundle\SlackCommand;

use Slack\Channel;
use Slack\User;

abstract class AbstractCommand
{
    protected $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    abstract public function execute(string $message, User $user, Channel $channel);
}
