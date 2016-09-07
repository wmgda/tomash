<?php

namespace AppBundle\SlackCommand;

use Slack\Channel;
use Slack\User;
use Spatie\Regex\Regex;

class UrlopCommand extends AbstractCommand
{
    public function execute(string $message, User $user, Channel $channel)
    {
        parent::execute($message, $user, $channel);

        $urlopRegex = Regex::match('/urlop (.+)/', $message);
        if ($urlopRegex->hasMatch()) {
            $this->reply('Udanego wypoczynku! :) :sunny:');
        }

        $delegacjaRegex = Regex::match('/delegacja (.+)/', $message);
        if ($delegacjaRegex->hasMatch()) {
            $this->reply('Nie wydaj za dużo! ;) :moneybag:');
        }

        $wfhRegex = Regex::match('/wfh (.+)/', $message);
        if ($wfhRegex->hasMatch()) {
            $this->reply('Mam nadzieję, że jednak będziesz pracował ;) :house:');
        }

        $zwolnienieRegex = Regex::match('/zwolnienie (.+)/', $message);
        if ($zwolnienieRegex->hasMatch()) {
            $this->reply('Szybkiego powrotu do zdrowia! :face_with_thermometer:');
        }
    }
}
