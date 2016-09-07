<?php
declare(strict_types = 1);

namespace Domain\UseCase\Absence;

use Domain\Exception\AbsenceException;
use Domain\Model\Absence\Absence;
use Domain\UseCase\Absence\WorkFromHome\Command;
use Domain\UseCase\Absence\WorkFromHome\Responder;
use Infrastructure\File\AbsenceStorage;

class WorkFromHome
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
                Absence::ABSENCE_TYPE_WORK_FROM_HOME,
                $command->getPerson(),
                $command->getDateStart(),
                $command->getDateEnd()
            );
            $this->storage->add($absence);
        } catch (AbsenceException $exception) {
            return $responder->failedToWorkFormHome($exception);
        }

        return $responder->workFromHomeSuccessfully();
    }
}
