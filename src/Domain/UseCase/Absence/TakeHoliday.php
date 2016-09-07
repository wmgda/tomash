<?php
declare(strict_types = 1);

namespace Domain\UseCase\Absence;

use Domain\Exception\AbsenceException;
use Domain\Model\Absence\Absence;
use Domain\UseCase\Absence\TakeHoliday\Command;
use Domain\UseCase\Absence\TakeHoliday\Responder;
use Infrastructure\File\AbsenceStorage;

class TakeHoliday
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
                Absence::ABSENCE_TYPE_HOLIDAY,
                $command->getPerson(),
                $command->getDateStart(),
                $command->getDateEnd()
            );
            $this->storage->add($absence);
        } catch (AbsenceException $exception) {
            return $responder->failedToTakeHoliday($exception);
        }

        return $responder->holidayTakenSuccessfully();
    }
}
