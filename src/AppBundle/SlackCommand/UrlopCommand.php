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
            preg_match('/urlop(?<date>.+)/', $message, $results);
            $period = $this->getPeriod($results['date']);

            $this->reply('Udanego wypoczynku! :) :sunny:');
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

            $this->reply('Mam nadzieję, że jednak będziesz pracował ;) :house:');
        }

        $zwolnienieRegex = Regex::match('/zwolnienie (.+)/', $message);
        if ($zwolnienieRegex->hasMatch()) {
            preg_match('/zwolnienie(?<date>.+)/', $message, $results);
            $period = $this->getPeriod($results['date']);

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
        $this->reply('Nigdzie nie jedziesz! Wracaj do roboty! :(');
    }

    private function getPeriod(string $date)
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
