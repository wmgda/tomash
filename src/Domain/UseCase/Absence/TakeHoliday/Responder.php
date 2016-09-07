<?php
declare(strict_types = 1);

namespace Domain\UseCase\Absence\TakeHoliday;

use Domain\Exception\AbsenceException;

interface Responder
{
    /**
     * Returned when delegation was taken successfully
     */
    public function holidayTakenSuccessfully();

    /**
     * Returned when something goes wrong
     * @param AbsenceException $exception
     */
    public function failedToTakeHoliday(AbsenceException $exception);
}
