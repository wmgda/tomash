<?php

namespace Application\AppBundle\SlackCommand;

use Slack\Channel;
use Slack\User;
use Spatie\Regex\MatchResult;
use Spatie\Regex\Regex;

abstract class TempAbstractCommand extends AbstractCommand
{
    /** @var string */
    private $regexPattern;

    /** @var MatchResult */
    private $regex;

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
}
