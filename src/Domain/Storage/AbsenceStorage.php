<?php
declare(strict_types = 1);

namespace Domain\Storage;

use Domain\Model\Absence\Absence;

interface AbsenceStorage
{
    public function add(Absence $absence);

    public function find(string $date, string $person);
}
