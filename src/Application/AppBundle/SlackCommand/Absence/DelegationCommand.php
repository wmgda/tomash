<?php

namespace Application\AppBundle\SlackCommand\Absence;

use Application\AppBundle\SlackCommand\TempAbstractCommand;
use Domain\Exception\AbsenceException;
use Domain\UseCase\Absence\TakeDelegation;
use Domain\UseCase\Absence\TakeHoliday;
use Domain\UseCase\Absence\TakeSickLeave;
use Domain\UseCase\Absence\WorkFromHome;
use Infrastructure\File\AbsenceStorage;
use Slack\Channel;
use Slack\User;

class DelegationCommand extends TempAbstractCommand implements TakeDelegation\Responder
{
    public function configure()
    {
        $this->setRegex('/delegacja (.+)/iu');
    }

    public function execute(string $message, User $user, Channel $channel)
    {
        parent::execute($message, $user, $channel);

        $period = $this->getPeriod($this->getPart(1));

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

    public function delegationTakenSuccessfully()
    {
        $this->reply('Nie wydaj za dużo! ;) :moneybag:');
    }

    public function failedToTakeDelegation(AbsenceException $exception)
    {
        $this->reply('Nigdzie nie jedziesz! Wracaj do roboty! :(');
    }
}
