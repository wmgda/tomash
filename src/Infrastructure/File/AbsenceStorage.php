<?php
declare(strict_types = 1);

namespace Infrastructure\File;

use Domain\Storage\AbsenceStorage as Storage;
use Domain\Model\Absence\Absence;
use League\Csv\Writer;
use SplFileObject;

class AbsenceStorage implements Storage
{
    private static $file = './var/storage/absence.csv';

    public function add(Absence $absence)
    {
        $writer = Writer::createFromFileObject(new SplFileObject(self::$file, 'a+'));
        $writer->insertAll($absence->toStorage());
    }
}
