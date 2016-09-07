<?php
declare(strict_types = 1);

namespace Domain\UseCase\Absence\WorkFromHome;

use Domain\Exception\AbsenceException;

interface Responder
{
    public function failedToWorkFormHome(AbsenceException $exception);

    public function workFromHomeSuccessfully();
}
