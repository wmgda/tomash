<?php
declare(strict_types = 1);

namespace Domain\UseCase\Absence;

use Domain\Exception\AbsenceException;
use Domain\Model\Absence\Absence;
use Domain\UseCase\Absence\TakeDelegation\Command;
use Domain\UseCase\Absence\TakeDelegation\Responder;
use Infrastructure\File\AbsenceStorage;

class TakeDelegation
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
                $command->getPerson(),
                $command->getDate(),
                Absence::ABSENCE_TYPE_DELEGATION
            );
            $this->storage->add($absence);
        } catch (AbsenceException $exception) {
            return $responder->failedToTakeDelegation($exception);
        }

        return $responder->delegationTakenSuccessfully();
    }
}
