<?php

namespace Application\AppBundle\SlackCommand\Absence;

use Application\AppBundle\SlackCommand\TempAbstractCommand;
use Domain\Model\Absence\Absence;
use Domain\UseCase\Absence\ListAbsent;
use Infrastructure\File\AbsenceStorage;
use Slack\Channel;
use Slack\Message\Attachment;
use Slack\Message\MessageBuilder;
use Slack\User;

class WhoIsAbsentCommand extends TempAbstractCommand implements ListAbsent\Responder
{
    public function configure()
    {
        $this->setRegex('/nieobecni (.+)/');
    }

    public function execute(string $message, User $user, Channel $channel)
    {
        parent::execute($message, $user, $channel);

        $period = $this->getPeriod($this->getPart(1));

        $useCase = new ListAbsent(new AbsenceStorage());
        $useCase->execute(new ListAbsent\Command($period['startDate']), $this);
    }

    public function allAreAtWork()
    {
        $this->reply('Wszyscy pracują. Niewiarygodne!');
    }

    public function absentWorkersListedSuccessfully(string $date, array $absenceData)
    {
        $this->advancedReply(function (MessageBuilder $builder) use ($date, $absenceData) {
            $lines = [];
            $builder->setText('<@' . $this->user->getId() . '> ');

            foreach ($absenceData as $user => $reason) {
                $lines[] = sprintf('%s %s', $user, Absence::reason($reason));
            }

            $attachment = new Attachment('Nieobecni '.$date, implode($lines, "\n"));
            $builder->addAttachment($attachment);

            return $builder;
        });
    }

    public function absentWorkersListFailed()
    {
        $this->reply('Coś nie zadziałało. Trzeba policzyć ręcznie.');
    }
}
