<?php

namespace Application\AppBundle\Slack\Command\Absence;

use Application\AppBundle\Slack\Command\AbstractCommand;
use Domain\Exception\AbsenceException;
use Domain\UseCase\Absence\TakeDelegation;
use Domain\UseCase\Absence\TakeHoliday;
use Domain\UseCase\Absence\TakeSickLeave;
use Domain\UseCase\Absence\WorkFromHome;
use Infrastructure\File\AbsenceStorage;
use Slack\Channel;
use Slack\User;

class HolidayCommand extends AbstractCommand implements TakeHoliday\Responder
{
    public function configure()
    {
        $this->setRegex('/urlop (.+)/iu');
    }

    public function execute(string $message, User $user, Channel $channel)
    {
        parent::execute($message, $user, $channel);

        $period = $this->getPeriod($this->getPart(1));

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

    public function holidayTakenSuccessfully()
    {
        $this->reply('Udanego wypoczynku! :) :sunny:');
    }

    public function failedToTakeHoliday(AbsenceException $exception)
    {
        $this->reply('W pracy nie pada!');
    }
}