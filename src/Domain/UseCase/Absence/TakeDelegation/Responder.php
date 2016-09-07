<?php
declare(strict_types = 1);

namespace Domain\UseCase\Absence\TakeDelegation;

use Domain\Exception\AbsenceException;

interface Responder
{
    /**
     * Returned when delegation was taken successfully
     */
    public function delegationTakenSuccessfully();

    /**
     * Returned when something goes wrong
     * @param AbsenceException $exception
     */
    public function failedToTakeDelegation(AbsenceException $exception);
}
