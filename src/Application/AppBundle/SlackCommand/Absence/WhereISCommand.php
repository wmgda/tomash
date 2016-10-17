<?php

namespace Application\AppBundle\SlackCommand\Absence;

use Application\AppBundle\SlackCommand\TempAbstractCommand;
use Domain\Model\Absence\Absence;
use Domain\UseCase\Absence\WhereIs;
use Infrastructure\File\AbsenceStorage;
use Slack\Channel;
use Slack\User;

class WhereIsCommand extends TempAbstractCommand implements WhereIs\Responder
{
    public function configure()
    {
        $this->setRegex('/gdzie jest (.+)/iu');
    }

    public function execute(string $message, User $user, Channel $channel)
    {
        parent::execute($message, $user, $channel);

        $name = trim($this->getPart(1));

        $useCase = new WhereIs(new AbsenceStorage());
        $useCase->execute(new WhereIs\Command(new \DateTime(), $name), $this);
    }

    public function entryNotFoundForPerson(string $person)
    {
        $this->reply($person.' jest (powinien/powinna być) dzisiaj w pracy!');
    }

    public function personIs(array $absenceData)
    {
        $this->reply($absenceData['person'].' '.Absence::reason($absenceData['type']));
    }
}
