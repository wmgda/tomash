<?php
declare(strict_types = 1);

namespace Domain\UseCase\Absence\TakeSickLeave;

use Domain\Exception\AbsenceException;

interface Responder
{
    /**
     * Returned when sick leave was taken successfully
     */
    public function sickLeaveTakenSuccessfully();

    /**
     * Returned when something goes wrong
     * @param AbsenceException $exception
     */
    public function failedToTakeSickLeave(AbsenceException $exception);

}
