<?php

namespace Application\AppBundle\SlackCommand\Absence;

use Application\AppBundle\SlackCommand\AbstractCommand;
use Domain\Model\Absence\Absence;
use Domain\UseCase\Absence\WhereIs;
use Infrastructure\File\AbsenceStorage;
use Slack\Channel;
use Slack\User;
use Spatie\Regex\Regex;

class WhereIsCommand extends AbstractCommand implements WhereIs\Responder
{
    public function execute(string $message, User $user, Channel $channel)
    {
        parent::execute($message, $user, $channel);

        $gdzieJestRegex = Regex::match('/gdzie jest (.+)/', $message);

        if ($gdzieJestRegex->hasMatch()) {
            preg_match('/gdzie jest(?<name>.+)/', $message, $results);
            $name = trim($results['name']);

            $useCase = new WhereIs(new AbsenceStorage());
            $useCase->execute(new WhereIs\Command(new \DateTime(), $name), $this);
        }
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
