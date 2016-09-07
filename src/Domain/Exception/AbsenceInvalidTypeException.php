<?php
declare(strict_types=1);

namespace Domain\Exception;

class AbsenceInvalidTypeException extends AbsenceException
{
    public function __construct()
    {
        $this->message = 'Invalid absence type.';
    }

}
