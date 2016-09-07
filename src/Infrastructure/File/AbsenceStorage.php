<?php
declare(strict_types = 1);

namespace Infrastructure\File;

use Domain\Storage\AbsenceStorage as Storage;
use Domain\Model\Absence\Absence;
use League\Csv\Reader;
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

    public function find(string $date, string $person)
    {
        $reader = Reader::createFromPath(self::$file);

        $data = [];
        foreach ($reader->fetchAll() as $entry) {
            if ($date == $entry[1]) {
                $data[$entry[0]] = $entry[2];
            }
        }

        if (!empty($person)) {
            return [
                'person' => $person,
                'type' => $data[$person]
            ];
        }

        return $data;
    }
}
