<?php

namespace Application\AppBundle\Slack\Command;

use Slack\ApiClient;
use Slack\Channel;
use Slack\User;
use Spatie\Regex\MatchResult;
use Spatie\Regex\Regex;

abstract class AbstractCommand
{
    /** @var ApiClient */
    protected $client;

    /** @var User */
    protected $user;

    /** @var Channel */
    protected $channel;

    /** @var string */
    protected $regexPattern;

    /** @var MatchResult */
    protected $regex;

    public function __construct(ApiClient $client)
    {
        $this->client = $client;
    }

    public function setRegex(string $regex)
    {
        $this->regexPattern = $regex;
    }

    public function matches(string $message) : bool
    {
        $this->regex = Regex::match($this->regexPattern, $message);

        return $this->regex->hasMatch();
    }

    public function getPart(int $index) : string
    {
        return $this->regex->group($index);
    }

    public function execute(string $message, User $user, Channel $channel)
    {
        $this->user = $user;
        $this->channel = $channel;
    }

    protected function reply(string $message)
    {
        $this->client->send('<@' . $this->user->getId() . '> ' .$message, $this->channel);
    }

    protected function advancedReply(callable $callback)
    {
        $messageBuilder = $this->client->getMessageBuilder();
        $messageBuilder->setChannel($this->channel);

        $message = $callback($messageBuilder)->create();

        $this->client->postMessage($message);
    }

    protected function getPeriod(string $date)
    {
        $date = trim($date);

        $startDate = null;
        $endDate = null;

        if ($date === 'jutro') {
            $startDate = new \DateTime('+1 day');
        }

        if ($date === 'dziś' || $date === 'dzis' || $date === 'dzisiaj') {
            $startDate = new \DateTime('now');
        }

        $datesRegex = Regex::match('/(\d{1,2}).(\d{1,2})-(\d{1,2}).(\d{1,2})/', $date);
        if ($datesRegex->hasMatch()) {
            $dayStart = $datesRegex->group(1);
            $monthStart = $datesRegex->group(2);
            $dayEnd = $datesRegex->group(3);
            $monthEnd = $datesRegex->group(4);

            $startDate = new \DateTime($monthStart . '/' . $dayStart);
            $endDate = new \DateTime($monthEnd . '/' . $dayEnd);
        }

        $datesRegex = Regex::match('/(\d{1,2}).(\d{1,2})/', $date);
        if ($datesRegex->hasMatch()) {
            $dayStart = $datesRegex->group(1);
            $monthStart = $datesRegex->group(2);

            $startDate = new \DateTime($monthStart . '/' . $dayStart);
        }

        if (empty($endDate)) {
            $endDate = $startDate;
        }

        return ['startDate' => $startDate, 'endDate' => $endDate];
    }
}
