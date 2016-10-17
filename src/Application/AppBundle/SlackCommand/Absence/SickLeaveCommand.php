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

class SickLeaveCommand extends AbstractCommand implements TakeSickLeave\Responder
{
    public function configure()
    {
        $this->setRegex('/zwolnienie (.+)/iu');
    }

    public function execute(string $message, User $user, Channel $channel)
    {
        parent::execute($message, $user, $channel);

        $period = $this->getPeriod($this->getPart(1));

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

    public function sickLeaveTakenSuccessfully()
    {
        $this->reply('Szybkiego powrotu do zdrowia! :face_with_thermometer:');
    }

    public function failedToTakeSickLeave(AbsenceException $exception)
    {
        $this->reply('Nie symuluj! Wracaj do roboty! :(');
    }
}
