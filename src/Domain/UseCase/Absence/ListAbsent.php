<?php
declare(strict_types = 1);

namespace Domain\UseCase\Absence;

use Domain\Exception\AbsenceException;
use Domain\UseCase\Absence\ListAbsent\Command;
use Domain\UseCase\Absence\ListAbsent\Responder;
use Infrastructure\File\AbsenceStorage;

class ListAbsent
{
    private $storage;

    /**
     * ListAbsent constructor.
     * @param $storage
     */
    public function __construct(AbsenceStorage $storage)
    {
        $this->storage = $storage;
    }

    public function execute(Command $command, Responder $responder)
    {
        $absenceData = [];
        try {
            $absenceData = $this->storage->find($command->getDate(), '');
        } catch (AbsenceException $exception) {
            $responder->absentWorkersListFailed();
        }

        if (empty($absenceData)) {
            $responder->allAreAtWork();
        }

        $responder->absentWorkersListedSuccessfully($command->getDate(), $absenceData);
    }
}
