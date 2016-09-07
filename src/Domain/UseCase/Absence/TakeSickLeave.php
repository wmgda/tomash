<?php
declare(strict_types = 1);

namespace Domain\UseCase\Absence;

use Domain\Exception\AbsenceException;
use Domain\Model\Absence\Absence;
use Domain\UseCase\Absence\TakeSickLeave\Command;
use Domain\UseCase\Absence\TakeSickLeave\Responder;
use Infrastructure\File\AbsenceStorage;

class TakeSickLeave
{
    /**
     * @var AbsenceStorage
     */
    private $storage;

    /**
     * TakeDelegation constructor.
     * @param AbsenceStorage $absenceStorage
     */
    public function __construct(AbsenceStorage $absenceStorage)
    {
        $this->storage = $absenceStorage;
    }

    public function execute(Command $command, Responder $responder)
    {
        try {
            $absence = new Absence(
                Absence::ABSENCE_TYPE_SICK_LEAVE,
                $command->getPerson(),
                $command->getDateStart(),
                $command->getDateEnd()
            );
            $this->storage->add($absence);
        } catch (AbsenceException $exception) {
            return $responder->failedToTakeSickLeave($exception);
        }

        return $responder->sickLeaveTakenSuccessfully();
    }
}
