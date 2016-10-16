<?php

namespace Application\AppBundle\SlackCommand\Absence;

use Application\AppBundle\SlackCommand\AbstractCommand;
use Domain\Exception\AbsenceException;
use Domain\UseCase\Absence\TakeDelegation;
use Domain\UseCase\Absence\TakeHoliday;
use Domain\UseCase\Absence\TakeSickLeave;
use Domain\UseCase\Absence\WorkFromHome;
use Infrastructure\File\AbsenceStorage;
use Slack\Channel;
use Slack\User;
use Spatie\Regex\Regex;

class AbsenceCommand extends AbstractCommand implements
    TakeDelegation\Responder,
    TakeHoliday\Responder,
    TakeSickLeave\Responder,
    WorkFromHome\Responder
{
    public function execute(string $message, User $user, Channel $channel)
    {
        parent::execute($message, $user, $channel);

        $urlopRegex = Regex::match('/urlop (.+)/', $message);
        if ($urlopRegex->hasMatch()) {
            preg_match('/urlop(?<date>.+)/', $message, $results);
            $period = $this->getPeriod($results['date']);

            $useCase = new TakeHoliday(new AbsenceStorage());
            $useCase->execute(
                new TakeHoliday\Command(
                    $user->getUsername(),
                    $period['startDate'],
                    $period['endDate']
                ),
                $this
            );
        }

        $delegacjaRegex = Regex::match('/delegacja (.+)/', $message);
        if ($delegacjaRegex->hasMatch()) {
            preg_match('/delegacja(?<date>.+)/', $message, $results);
            $period = $this->getPeriod($results['date']);

            $useCase = new TakeDelegation(new AbsenceStorage());
            $useCase->execute(
                new TakeDelegation\Command(
                    $user->getUsername(),
                    $period['startDate'],
                    $period['endDate']
                ),
                $this
            );
        }

        $wfhRegex = Regex::match('/wfh (.+)/', $message);
        if ($wfhRegex->hasMatch()) {
            preg_match('/wfh(?<date>.+)/', $message, $results);
            $period = $this->getPeriod($results['date']);

            $useCase = new WorkFromHome(new AbsenceStorage());
            $useCase->execute(
                new WorkFromHome\Command(
                    $user->getUsername(),
                    $period['startDate'],
                    $period['endDate']
                ),
                $this
            );
        }

        $zwolnienieRegex = Regex::match('/zwolnienie (.+)/', $message);
        if ($zwolnienieRegex->hasMatch()) {
            preg_match('/zwolnienie(?<date>.+)/', $message, $results);
            $period = $this->getPeriod($results['date']);

            $useCase = new TakeSickLeave(new AbsenceStorage());
            $useCase->execute(
                new TakeSickLeave\Command(
                    $user->getUsername(),
                    $period['startDate'],
                    $period['endDate']
                ),
                $this
            );
        }
    }

    public function delegationTakenSuccessfully()
    {
        $this->reply('Nie wydaj za dużo! ;) :moneybag:');
    }

    public function failedToTakeDelegation(AbsenceException $exception)
    {
        $this->reply('Nigdzie nie jedziesz! Wracaj do roboty! :(');
    }

    public function holidayTakenSuccessfully()
    {
        $this->reply('Udanego wypoczynku! :) :sunny:');
    }

    public function failedToTakeHoliday(AbsenceException $exception)
    {
        $this->reply('W pracy nie pada!');
    }

    public function sickLeaveTakenSuccessfully()
    {
        $this->reply('Szybkiego powrotu do zdrowia! :face_with_thermometer:');
    }

    public function failedToTakeSickLeave(AbsenceException $exception)
    {
        $this->reply('Nie symuluj! Wracaj do roboty! :(');
    }

    public function failedToWorkFormHome(AbsenceException $exception)
    {
        $this->reply('Nie poradzimy sobie BEZ Ciebie!');
    }

    public function workFromHomeSuccessfully()
    {
        $this->reply('Mam nadzieję, że jednak będziesz pracował ;) :house:');
    }
}
