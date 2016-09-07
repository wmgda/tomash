<?php

namespace AppBundle\SlackCommand;

use Slack\ApiClient;
use Slack\Channel;
use Slack\User;

abstract class AbstractCommand
{
    /** @var ApiClient */
    protected $client;

    /** @var User */
    protected $user;

    /** @var Channel */
    protected $channel;

    public function __construct(ApiClient $client)
    {
        $this->client = $client;
    }

    public function execute(string $message, User $user, Channel $channel)
    {
        $this->user = $user;
        $this->channel = $channel;
    }

    protected function reply(string $message)
    {
        $this->client->send('@' . $this->user->getUsername() . ' ' . $message, $this->channel);
    }

    protected function advancedReply(callable $callback)
    {
        $messageBuilder = $this->client->getMessageBuilder();
        $messageBuilder->setChannel($this->channel);

        $message = $callback($messageBuilder);

        $this->client->postMessage($message);
    }
}
