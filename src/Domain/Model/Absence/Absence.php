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
     * @var \DateTime
     */
    private $dateStart;

    /**
     * @var \DateTime
     */
    private $dateEnd;

    /**
     * @var int
     */
    private $type;

    /**
     * Absence constructor.
     * @param int $type
     * @param string $person
     * @param \DateTime $dateStart
     * @param \DateTime $dateEnd
     */
    public function __construct(int $type, string $person, \DateTime $dateStart, \DateTime $dateEnd)
    {
        $this->validateAbsenceTypes($type);

        $this->person = $person;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
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
     * @return \DateTime
     */
    public function getDateStart(): \DateTime
    {
        return $this->dateStart;
    }

    /**
     * @return \DateTime
     */
    public function getDateEnd(): \DateTime
    {
        return $this->dateEnd;
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

    public function toStorage() : array
    {
        $data = [];

        $diff = $this->dateStart->diff($this->dateEnd)->days;

        for ($i = 0; $i <= $diff; $i++) {
            $data[] = [
                'person' => $this->getPerson(),
                'date' => $this->getDateStart()->format('d-m-Y'),
                'type' => $this->getType(),
            ];
            $this->dateStart->add(new \DateInterval('P1D'));
        }

        return $data;
    }

    public static function reason(int $type) {
        switch ($type) {
            case self::ABSENCE_TYPE_DELEGATION:
                return 'jest w delegacji';
            case self::ABSENCE_TYPE_HOLIDAY:
                return 'jest na urlopie';
            case self::ABSENCE_TYPE_SICK_LEAVE:
                return 'jest na zwolnieniu lekarskim';
            case self::ABSENCE_TYPE_WORK_FROM_HOME:
                return 'pracuje z domu';
        }
    }
}
