<?php
declare(strict_types = 1);

namespace Domain\UseCase\Absence;

use Domain\Exception\AbsenceException;
use Domain\UseCase\Absence\WhereIs\Command;
use Domain\UseCase\Absence\WhereIs\Responder;
use Infrastructure\File\AbsenceStorage;

class WhereIs
{
    private $storage;

    /**
     * WhereIs constructor.
     */
    public function __construct(AbsenceStorage $storage)
    {
        $this->storage = $storage;
    }

    public function execute(Command $command, Responder $responder)
    {
        $absenceData = [];
        try {
            $absenceData = $this->storage->find($command->getDate(), $command->getPerson());
        } catch (AbsenceException $exception) {
            $responder->entryNotFoundForPerson($command->getPerson());
        }

        if (empty($absenceData)) {
            $responder->entryNotFoundForPerson($command->getPerson());
        }

        $responder->personIs($absenceData);
    }
}
