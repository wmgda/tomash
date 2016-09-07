<?php

namespace AppBundle\SlackCommand;

use Domain\Exception\AbsenceException;
use Domain\UseCase\Absence\TakeDelegation;
use Infrastructure\File\AbsenceStorage;
use Slack\Channel;
use Slack\User;
use Spatie\Regex\Regex;

class UrlopCommand extends AbstractCommand implements TakeDelegation\Responder
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
            $useCase = new TakeDelegation(new AbsenceStorage());
            $useCase->execute(new TakeDelegation\Command('ulff', '8.09.2016'), $this);
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

    /**
     * Returned when delegation was taken successfully
     */
    public function delegationTakenSuccessfully()
    {
        $this->reply('Nie wydaj za dużo! ;) :moneybag:');
    }

    /**
     * Returned when something goes wrong
     * @param AbsenceException $exception
     */
    public function failedToTakeDelegation(AbsenceException $exception)
    {
        $this->reply('Coś się zjebało :(');
    }
}
