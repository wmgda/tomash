<?php
declare(strict_types = 1);

namespace Domain\Model\Absence;

use Domain\Exception\AbsenceInvalidTypeException;

class Absence
{
    const ABSENCE_TYPE_DELEGATION = 1;
    const ABSENCE_TYPE_HOLIDAY = 2;
    const ABSENCE_TYPE_SICK_LEAVE = 4;
    const ABSENCE_TYPE_WORK_FROM_HOME = 8;

    private static $absence_types = [
        self::ABSENCE_TYPE_DELEGATION,
        self::ABSENCE_TYPE_HOLIDAY,
        self::ABSENCE_TYPE_SICK_LEAVE,
        self::ABSENCE_TYPE_WORK_FROM_HOME,
    ];

    /**
     * @var string
     */
    private $person;

    /**
     * @var string
     */
    private $date;

    /**
     * @var int
     */
    private $type;

    /**
     * Absence constructor.
     * @param string $person
     * @param string $date
     * @param int $type
     */
    public function __construct(string $person, string $date, int $type)
    {
        $this->validateAbsenceTypes($type);

        $this->person = $person;
        $this->date = $date;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getPerson(): string
    {
        return $this->person;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    private function validateAbsenceTypes($type)
    {
        if (!in_array($type, self::$absence_types)) {
            throw new AbsenceInvalidTypeException();
        }
    }

    public function toArray()
    {
        return [
            'person' => $this->getPerson(),
            'date' => $this->getDate(),
            'type' => $this->getType()
        ];
    }
}
