<?php
declare(strict_types = 1);

namespace Infrastructure\File;

use Domain\Model\Absence\Absence;
use League\Csv\Writer;
use SplTempFileObject;

class AbsenceStorage
{
    private static $file = './var/storage/absence.csv';

    public function add(Absence $absence)
    {
        $writer = Writer::createFromPath(new SplTempFileObject(self::$file, 'a+'));
        $writer->insertOne($absence->toArray());
    }
}
