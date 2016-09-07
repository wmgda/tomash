<?php
declare(strict_types = 1);

namespace Domain\UseCase\Absence\WhereIs;

interface Responder
{
    public function entryNotFoundForPerson(string $getPerson);

    public function personIs(array $absenceData);
}
