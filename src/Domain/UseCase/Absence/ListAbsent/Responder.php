<?php
declare(strict_types = 1);

namespace Domain\UseCase\Absence\ListAbsent;

interface Responder
{
    public function allAreAtWork();

    public function absentWorkersListedSuccessfully(string $date, array $absenceData);

    public function absentWorkersListFailed();
}
